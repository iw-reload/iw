<?php

namespace tests\codeception\common\unit\models;

use Yii;
use tests\codeception\common\unit\TestCase;
use Codeception\Specify;
use common\models\Universe;

/**
 * Login form test
 */
class UniverseTest extends TestCase
{
  use Specify;

  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  private $em = null;
  /**
   * @var \common\entities\Universe
   */
  private $universeEntity = null;
  
  public function setUp()
  {
    parent::setUp();
    
    /* @var $doctrine \common\components\doctrine\DoctrineComponent */
    $doctrine = Yii::$app->get('doctrine');
    $em = $doctrine->getEntityManager();

    $universeEntity = new \common\entities\Universe();
    $universeEntity->setMaxCelestialBodies( 2 );
    $universeEntity->setMaxSystems( 2 );
    $universeEntity->setMinCelestialBodies( 1 );
    $universeEntity->setMinSystems( 1 );
    $em->persist( $universeEntity );
    
    $asteroidSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $asteroidSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $asteroidSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $asteroidSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $asteroidSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $asteroidSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $asteroidSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $asteroidSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $asteroidSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $asteroidSpecs->getMaxSpecs()->setGravity( 5.0 );
    $asteroidSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $asteroidSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $asteroidSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $asteroidSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $asteroidSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $asteroidSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $asteroidSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $asteroidSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $asteroidSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $asteroidSpecs->getMinSpecs()->setGravity( 0.5 );
    $asteroidSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $asteroidSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_ASTEROID );
    $asteroidSpecs->setUniverse( $universeEntity );
    $em->persist( $asteroidSpecs );
    
    $electricityStormSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $electricityStormSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $electricityStormSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $electricityStormSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $electricityStormSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $electricityStormSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $electricityStormSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $electricityStormSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $electricityStormSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $electricityStormSpecs->getMaxSpecs()->setGravity( 5.0 );
    $electricityStormSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $electricityStormSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $electricityStormSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $electricityStormSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $electricityStormSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $electricityStormSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $electricityStormSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $electricityStormSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $electricityStormSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $electricityStormSpecs->getMinSpecs()->setGravity( 0.5 );
    $electricityStormSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $electricityStormSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_ELECTRICITY_STORM );
    $electricityStormSpecs->setUniverse( $universeEntity );
    $em->persist( $electricityStormSpecs );
    
    $gasGiantSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $gasGiantSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $gasGiantSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $gasGiantSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $gasGiantSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $gasGiantSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $gasGiantSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $gasGiantSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $gasGiantSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $gasGiantSpecs->getMaxSpecs()->setGravity( 5.0 );
    $gasGiantSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $gasGiantSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $gasGiantSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $gasGiantSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $gasGiantSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $gasGiantSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $gasGiantSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $gasGiantSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $gasGiantSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $gasGiantSpecs->getMinSpecs()->setGravity( 0.5 );
    $gasGiantSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $gasGiantSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_GAS_GIANT );
    $gasGiantSpecs->setUniverse( $universeEntity );
    $em->persist( $gasGiantSpecs );
    
    $gravimetricAnomalySpecs = new \common\entities\CelestialBodyTypeSpecs();
    $gravimetricAnomalySpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $gravimetricAnomalySpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $gravimetricAnomalySpecs->getMaxSpecs()->setGravity( 5.0 );
    $gravimetricAnomalySpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $gravimetricAnomalySpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $gravimetricAnomalySpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $gravimetricAnomalySpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $gravimetricAnomalySpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $gravimetricAnomalySpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $gravimetricAnomalySpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $gravimetricAnomalySpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $gravimetricAnomalySpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $gravimetricAnomalySpecs->getMinSpecs()->setGravity( 0.5 );
    $gravimetricAnomalySpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $gravimetricAnomalySpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_GRAVIMETRIC_ANOMALY );
    $gravimetricAnomalySpecs->setUniverse( $universeEntity );
    $em->persist( $gravimetricAnomalySpecs );
    
    $iceGiantSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $iceGiantSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $iceGiantSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $iceGiantSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $iceGiantSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $iceGiantSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $iceGiantSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $iceGiantSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $iceGiantSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $iceGiantSpecs->getMaxSpecs()->setGravity( 5.0 );
    $iceGiantSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $iceGiantSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $iceGiantSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $iceGiantSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $iceGiantSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $iceGiantSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $iceGiantSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $iceGiantSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $iceGiantSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $iceGiantSpecs->getMinSpecs()->setGravity( 0.5 );
    $iceGiantSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $iceGiantSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_ICE_GIANT );
    $iceGiantSpecs->setUniverse( $universeEntity );
    $em->persist( $iceGiantSpecs );
    
    $ionStormSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $ionStormSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $ionStormSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $ionStormSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $ionStormSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $ionStormSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $ionStormSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $ionStormSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $ionStormSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $ionStormSpecs->getMaxSpecs()->setGravity( 5.0 );
    $ionStormSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $ionStormSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $ionStormSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $ionStormSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $ionStormSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $ionStormSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $ionStormSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $ionStormSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $ionStormSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $ionStormSpecs->getMinSpecs()->setGravity( 0.5 );
    $ionStormSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $ionStormSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_ION_STORM );
    $ionStormSpecs->setUniverse( $universeEntity );
    $em->persist( $ionStormSpecs );
    
    $spatialDistortionSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $spatialDistortionSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $spatialDistortionSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $spatialDistortionSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $spatialDistortionSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $spatialDistortionSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $spatialDistortionSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $spatialDistortionSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $spatialDistortionSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $spatialDistortionSpecs->getMaxSpecs()->setGravity( 5.0 );
    $spatialDistortionSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $spatialDistortionSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $spatialDistortionSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $spatialDistortionSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $spatialDistortionSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $spatialDistortionSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $spatialDistortionSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $spatialDistortionSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $spatialDistortionSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $spatialDistortionSpecs->getMinSpecs()->setGravity( 0.5 );
    $spatialDistortionSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $spatialDistortionSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_SPATIAL_DISTORTION );
    $spatialDistortionSpecs->setUniverse( $universeEntity );
    $em->persist( $spatialDistortionSpecs );
    
    $terrestrialPlanetSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $terrestrialPlanetSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $terrestrialPlanetSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $terrestrialPlanetSpecs->getMaxSpecs()->setGravity( 5.0 );
    $terrestrialPlanetSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $terrestrialPlanetSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $terrestrialPlanetSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $terrestrialPlanetSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $terrestrialPlanetSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $terrestrialPlanetSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $terrestrialPlanetSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $terrestrialPlanetSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $terrestrialPlanetSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $terrestrialPlanetSpecs->getMinSpecs()->setGravity( 0.5 );
    $terrestrialPlanetSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $terrestrialPlanetSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_TERRESTRIAL_PLANET );
    $terrestrialPlanetSpecs->setUniverse( $universeEntity );
    $em->persist( $terrestrialPlanetSpecs );
    
    $voidSpecs = new \common\entities\CelestialBodyTypeSpecs();
    $voidSpecs->getMaxSpecs()->getEffects()->setBuildingCost( 1.0 );
    $voidSpecs->getMaxSpecs()->getEffects()->setBuildingTime( 1.0 );
    $voidSpecs->getMaxSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $voidSpecs->getMaxSpecs()->getEffects()->setResearchPoints( 1.0 );
    $voidSpecs->getMaxSpecs()->getEffects()->setTaxes( 1.0 );
    $voidSpecs->getMaxSpecs()->getResourceDensity()->setChemicals( 1.5 );
    $voidSpecs->getMaxSpecs()->getResourceDensity()->setIce( 1.5 );
    $voidSpecs->getMaxSpecs()->getResourceDensity()->setIron( 1.5 );
    $voidSpecs->getMaxSpecs()->setGravity( 5.0 );
    $voidSpecs->getMaxSpecs()->setLivingConditions( 2.0 );
    $voidSpecs->getMinSpecs()->getEffects()->setBuildingCost( 1.0 );
    $voidSpecs->getMinSpecs()->getEffects()->setBuildingTime( 1.0 );
    $voidSpecs->getMinSpecs()->getEffects()->setFleetScannerRange( 1.0 );
    $voidSpecs->getMinSpecs()->getEffects()->setResearchPoints( 1.0 );
    $voidSpecs->getMinSpecs()->getEffects()->setTaxes( 1.0 );
    $voidSpecs->getMinSpecs()->getResourceDensity()->setChemicals( 0.5 );
    $voidSpecs->getMinSpecs()->getResourceDensity()->setIce( 0.5 );
    $voidSpecs->getMinSpecs()->getResourceDensity()->setIron( 0.5 );
    $voidSpecs->getMinSpecs()->setGravity( 0.5 );
    $voidSpecs->getMinSpecs()->setLivingConditions( 0.5 );
    $voidSpecs->setCelestialBodyType( \common\entities\CelestialBody::DISCR_VOID );
    $voidSpecs->setUniverse( $universeEntity );
    $em->persist( $voidSpecs );
    
    $this->em = $em;
    $this->universeEntity = $universeEntity;
  }

  protected function tearDown()
  {
    parent::tearDown();
  }

  public function testAppendGalaxy()
  {
    $universeEntity = $this->universeEntity;
    $model = new Universe( $this->em, $this->universeEntity );

    $this->specify('universe should be able to expand', function () use ($universeEntity,$model) {
      $nGalaxies = $universeEntity->countGalaxies();
      $model->appendGalaxy();
      expect( 'after appending a galaxy, the universe should contain one '
        . 'galaxy more than before'
        , $universeEntity->countGalaxies() )
        ->equals( $nGalaxies + 1 );
    });
  }

}
