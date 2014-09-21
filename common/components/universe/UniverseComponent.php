<?php

namespace common\components\universe;

use common\models\CelestialBody;
use yii\base\Component;
use yii\base\InvalidCallException;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Description of UniverseComponent
 *
 * @author ben
 */
class UniverseComponent extends Component
{
  /**
    * @var string the application component ID of the DB connection.
    */
  public $db = 'db';

  public $galaxyCount = 2;
  public $avgSystemCount = 15;
  public $avgPlanetCount = 12;
  
  /**
   * @var float average desity of chemicals
   */
  public $avgChemicals = 100;
  /**
   * @var float variance of chemicals (min = avg - avg*var, max = avg + avg*var)
   */
  public $varChemicals = 0.5;
  /**
   * @var float average desity of iron
   */
  public $avgIron = 100;
  /**
   * @var float variance of iron (min = avg - avg*var, max = avg + avg*var)
   */
  public $varIron = 0.5;
  /**
   * @var float average desity of ice
   */
  public $avgIce = 30;
  /**
   * @var float variance of ice (min = avg - avg*var, max = avg + avg*var)
   */
  public $varIce = 0.5;
  /**
   * @var float
   */
  public $minGravitation = 0;
  /**
   * @var float
   */
  public $maxGravitation = 12;
  /**
   * @var float
   */
  public $minLivingConditions = 0;
  /**
   * @var float
   */
  public $maxLivingConditions = 250;
  
  /**
   * Returns the heatmap for the given galaxy. The heatmap defines, how dense
   * an area is populated. The heatmap is returned as an array where each entry
   * represents a system. A value of 0 means "cold" (no celestial body is
   * populated), a value of 1 means "hot" (all celestial bodies are populated).
   * 
   * @todo different heat maps once planet types are introduced.
   *       When creating the home for a new player, we need a cold area of only
   *       rocky planets, so the new player will get a chance of placing
   *       colonies.
   * 
   * @param int $gal
   */
  public function getHeatMap( $gal )
  {
    
  }
  
  /**
   * Creates a default celestial body for the given coordinates.
   * Its attributes will be set to the defaults. This method does not save the
   * celestial body after creating it.
   * 
   * @param int $gal
   * @param int $sys
   * @param int $pla
   */
  public function createCelestialBody( $gal, $sys, $pla )
  {
    $cb = CelestialBody::findOne([
      'pos_galaxy' => $gal,
      'pos_system' => $sys,
      'pos_planet' => $pla,
    ]);
    
    if ($cb instanceof CelestialBody)
    {
      $cbLabel = CelestialBody::createLabel( $gal, $sys, $pla );
      throw new InvalidCallException("Can't create celestian body '{$cbLabel}'. It already exists. Its Id is '{$cb->id}'.");
    }
    else
    {
      $cb = new CelestialBody();
      $cb->pos_galaxy = $gal;
      $cb->pos_system = $sys;
      $cb->pos_planet = $pla;
    }
    
    $cb->density_chemicals = 1.0;
    $cb->density_ice = 0.3;
    $cb->density_iron = 1.0;
    $cb->gravity = 1.0;
    $cb->living_conditions = 1.0;

    return $cb;
  }
  
  /**
   * Resets the celestial body at the given coordinates. Its attributes will be
   * reset to the defaults. This method does not save the celestial body after
   * modifying it.
   * 
   * @param int $id
   * @return CelestialBody
   */
  public function resetCelestialBody( $id )
  {
    $cb = CelestialBody::findOne([ 'id' => $id ]);
    
    if (!$cb instanceof CelestialBody) {
      throw new InvalidCallException("Can't reset celestian body '{$id}'. It does not exist.");
    }
    
    $cb->density_chemicals = 1.0;
    $cb->density_ice = 0.3;
    $cb->density_iron = 1.0;
    $cb->gravity = 1.0;
    $cb->living_conditions = 1.0;

    return $cb;
  }
  
  /**
   * Creates a new system of planets.
   * 
   * @param int $gal
   * @param int $sys
   * @return CelestialBody[]
   */
  public function createSystem( $gal, $sys )
  {
    // avg +/- 10%
    $minPlanets = $this->avgPlanetCount - $this->avgPlanetCount * 0.1;
    $maxPlanets = $this->avgPlanetCount + $this->avgPlanetCount * 0.1;
    
    $planetCount = rand( $minPlanets, $maxPlanets );
    $planets = [];
    
    for ($i = 0; $i < $planetCount; ++$i) {
      $planets[] = $this->createCelestialBody( $gal, $sys, $i );
    }
    
    foreach ($planets as $planet) {
      $this->shuffleCelestialBodyAttributes( $planet );
    }
    
    return $planets;
  }
  
  /**
   * Creates a new galaxy of planets.
   * 
   * @param int $gal
   * @return CelestialBody[]
   */
  public function createGalaxy( $gal )
  {
    // avg +/- 10%
    $minSystems = $this->avgSystemCount - $this->avgSystemCount * 0.1;
    $maxSystems = $this->avgSystemCount + $this->avgSystemCount * 0.1;
    
    $systemCount = rand( $minSystems, $maxSystems );
    $planets = [];
    
    for ($i = 0; $i < $systemCount; ++$i)
    {
      $systemPlanets = $this->createSystem( $gal, $i );
      $planets = array_merge( $planets, $systemPlanets );
    }
    
    return $planets;
  }
  
  /**
   * Creates a new universe.
   * 
   * @return CelestialBody[]
   */
  public function createUniverse()
  {
    $planets = [];
    
    for ($i = 0; $i < $this->galaxyCount; ++$i)
    {
      $galaxyPlanets = $this->createGalaxy( $i );
      $planets = array_merge( $planets, $galaxyPlanets );
    }
    
    return $planets;
  }
  
  /**
   * Selects a celestial body that should be used for a new player.
   * @todo could use a better algorithm. Maybe place new players in areas that
   *       are not too densly populated.
   *       Or place them among other players just having started playing.
   * @return CelestialBody
   */
  public function chooseCelestialBodyForNewPlayer()
  {
    $db = Instance::ensure( $this->db, Connection::className() );
    /* @var $db Connection */
    
    $sql = <<<'EOT'
SELECT {{cb}}.[[id]] AS [[id]],
FROM {{%celestial_body}} {{cb}}
LEFT JOIN {{%base}} {{b}} ON {{b}}.[[id]] = {{cb}}.[[id]]
WHERE {{b}}.[[id]] IS NULL
;
EOT;

    $recordSets = $db->createCommand( $sql )->queryAll();
    $randomOffset = rand( 0, count($recordSets) - 1);
    $randomRecordSet = $recordSets[ $randomOffset ];
    
    return CelestialBody::findOne(['id' => $randomRecordSet['id']]);
  }
  
  /**
   * @todo Maybe refine this. Better resources values might mean worse gravity/
   *       living conditions.
   *       Also, gravity has a wide range. Produce more planets within the
   *       "normal" zone.
   *      Maybe use binomial distribution.
   * @param CelestialBody $cb
   */
  private function shuffleCelestialBodyAttributes( $cb )
  {
    $minChemicals = $this->avgChemicals - $this->avgChemicals * $this->varChemicals;
    $maxChemicals = $this->avgChemicals + $this->avgChemicals * $this->varChemicals;
    
    $minIce = $this->avgIce - $this->avgIce * $this->varIce;
    $maxIce = $this->avgIce + $this->avgIce * $this->varIce;
    
    $minIron = $this->avgIron - $this->avgIron * $this->varIron;
    $maxIron = $this->avgIron + $this->avgIron * $this->varIron;
    
    $cb->density_chemicals = rand( $minChemicals, $maxChemicals ) / 100.0;
    $cb->density_ice = rand( $minIce, $maxIce ) / 100.0;
    $cb->density_iron = rand( $minIron, $maxIron ) / 100.0;
    $cb->gravity = rand( $this->minGravitation * 100, $this->maxGravitation * 100 ) / 100.0;
    $cb->living_conditions = rand( $this->minLivingConditions, $this->maxLivingConditions ) / 100;
  }
}
