<?php

namespace common\models;

use common\entities\CelestialBody as CelestialBodyEntity;
use common\entities\CelestialBodySpecialty as CelestialBodySpecialtyEntity;
use common\entities\Galaxy as GalaxyEntity;
use common\entities\System as SystemEntity;
use common\entities\Universe as UniverseEntity;
use common\entityRepositories\CelestialBody as CelestialBodyRepository;
use common\entityRepositories\CelestialBodySpecialty as CelestialBodySpecialtyRepository;
use common\models\Galaxy as GalaxyModel;
use common\objects\CelestialBodyFactory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author ben
 */
class Universe
{
  /**
   * Every planet has a 1:100 chance for every specialty to get it assigned.
   */
  const SPECIALTY_LIKELIHOOD = 100;
  
  /**
   * @var CelestialBodyRepository
   */
  private $celestialBodyRepository = null;
  /**
   * @var CelestialBodySpecialtyRepository
   */
  private $celestialBodySpecialtyRepository = null;
  /**
   * @var EntityManagerInterface
   */
  private $em = null;
  /**
   * @var UniverseEntity
   */   
  private $universeEntity = null;
  
  public function __construct
    (EntityManagerInterface $em
    , UniverseEntity $universeEntity
    , CelestialBodyRepository $celestialBodyRepository
    , CelestialBodySpecialtyRepository $celestialBodySpecialtyRepository
    ) {
    $this->celestialBodyRepository = $celestialBodyRepository;
    $this->celestialBodySpecialtyRepository = $celestialBodySpecialtyRepository;
    $this->em = $em;
    $this->universeEntity = $universeEntity;
  }

  private function appendCelestialBody(SystemEntity $systemEntity)
  {
    $celestialBodyEntity = $this->createCelestialBody( $systemEntity );
    $celestialBodyEntity->setNumber( $systemEntity->countCelestialBodies() );
    $celestialBodyEntity->setSystem( $systemEntity );
    
    $this->em->persist( $celestialBodyEntity );
    
    $this->shuffleCelestialBodySpecs( $celestialBodyEntity );
  }
  
  public function appendGalaxy()
  {
    $galaxyEntity = new GalaxyEntity();
    $galaxyEntity->setNumber( $this->universeEntity->countGalaxies() );
    $galaxyEntity->setUniverse( $this->universeEntity );
    
    $this->em->persist( $galaxyEntity );
    
    $systemCount = rand(
      $this->universeEntity->getMinSystems()
      , $this->universeEntity->getMaxSystems() - 1
      );
    
    for ($i = 0; $i < $systemCount; ++$i) {
      $this->appendSystem( $galaxyEntity );
    }
  }
  
  private function appendSystem(GalaxyEntity $galaxyEntity)
  {
    $systemEntity = new SystemEntity();
    $systemEntity->setGalaxy( $galaxyEntity );
    $systemEntity->setNumber( $galaxyEntity->countSystems() );
    
    $this->em->persist( $systemEntity );
    
    $celestialBodyCount = rand(
      $this->universeEntity->getMinCelestialBodies()
      , $this->universeEntity->getMaxCelestialBodies() - 1
      );

    for ($i = 0; $i < $celestialBodyCount; ++$i) {
      $this->appendCelestialBody( $systemEntity );
    }
  }
  
  /**
   * Selects a celestial body that should be used for a new player.
   * 
   * @todo could use a better algorithm. Maybe place new players in areas that
   *       are not too densly populated.
   *       Or place them among other players just having started playing.
   *       Currently, we simply take any celestial body that doesn't yet have
   *       an owner.
   * 
   * @return \common\models\TerrestrialPlanet
   */
  public function getTerrestrialPlanetForNewPlayer()
  {
    $celestialBodyEntity = $this->celestialBodyRepository->findForNewPlayer();
    
    if ($celestialBodyEntity instanceof \common\entities\TerrestrialPlanet)
    {
      $terrestrialPlanetEntity = $celestialBodyEntity;
    }
    else
    {
      $this->em->detach( $celestialBodyEntity );

      // This is the bad part.
      // Maybe there's a better alternative?
      $discrTerrestrialPlanet = CelestialBodyEntity::DISCR_TERRESTRIAL_PLANET;
      $query = "UPDATE celestialbody SET discr = {$discrTerrestrialPlanet} WHERE id = {$celestialBodyEntity->getId()}";
      $this->em->getConnection()->exec( $query );

      // this is now a terrestrial planet
      $terrestrialPlanetEntity = $this->celestialBodyRepository->find( $celestialBodyEntity->getId() );
    }    
    
    $terrestrialPlanetModel = CelestialBodyFactory::create( $terrestrialPlanetEntity, $this->celestialBodySpecialtyRepository );
    $terrestrialPlanetModel->reset();
    
    return $terrestrialPlanetModel;
  }
  
  /**
   * @param SystemEntity $systemEntity
   * @return \common\entities\CelestialBody
   * @throws \yii\base\InvalidValueException
   */
  private function createCelestialBody(SystemEntity $systemEntity)
  {
    $galaxyModel = new GalaxyModel( $systemEntity->getGalaxy() );
    $allowedCelestialBodyTypes = $galaxyModel->getAllowedCelestialBodyTypes();
    $celestialBodyTypeIndex = rand( 0, count($allowedCelestialBodyTypes) - 1 );
    $celestialBodyType = $allowedCelestialBodyTypes[$celestialBodyTypeIndex];
    
    switch ($celestialBodyType)
    {
    case CelestialBodyEntity::DISCR_ASTEROID:
        $celestialBody = new \common\entities\Asteroid();
        break;
    case CelestialBodyEntity::DISCR_ELECTRICITY_STORM:
        $celestialBody = new \common\entities\ElectricityStorm();
        break;
    case CelestialBodyEntity::DISCR_GAS_GIANT:
        $celestialBody = new \common\entities\GasGiant();
        break;
    case CelestialBodyEntity::DISCR_GRAVIMETRIC_ANOMALY:
        $celestialBody = new \common\entities\GravimetricAnomaly();
        break;
    case CelestialBodyEntity::DISCR_ICE_GIANT:
        $celestialBody = new \common\entities\IceGiant();
        break;
    case CelestialBodyEntity::DISCR_ION_STORM:
        $celestialBody = new \common\entities\IonStorm();
        break;
    case CelestialBodyEntity::DISCR_SPATIAL_DISTORTION:
        $celestialBody = new \common\entities\SpatialDistortion();
        break;
    case CelestialBodyEntity::DISCR_TERRESTRIAL_PLANET:
        $celestialBody = new \common\entities\TerrestrialPlanet();
        break;
    case CelestialBodyEntity::DISCR_VOID:
        $celestialBody = new \common\entities\Void();
        break;
    default:
        throw new \yii\base\InvalidValueException("Can't create celestial body of type '{$celestialBodyType}'.");
    }
    
    return $celestialBody;
  }

  /**
   * @param int|double $min
   * @param int|double $max
   * @param double $precission
   * @return int|double
   */
  private function rand($min,$max,$precission=0.01)
  {
    if (is_double($min) || is_double($max))
    {
      $min /= $precission;
      $max /= $precission;
      $result = rand( $min, $max );
      return doubleval($result) * $precission;
    }
    else
    {
      return rand( $min, $max );
    }
  }  
  
  /**
   * @todo Maybe refine this. Better resources values might mean worse gravity/
   *       living conditions.
   *       Also, gravity has a wide range. Produce more planets within the
   *       "normal" zone.
   *       Maybe use binomial distribution.
   * @param CelestialBodyEntity $celestialBodyEntity
   */
  private function shuffleCelestialBodySpecs(CelestialBodyEntity $celestialBodyEntity)
  {
    $galaxyModel = new GalaxyModel( $celestialBodyEntity->getSystem()->getGalaxy() );
    $maxSpecs = $galaxyModel->getMaxCelestialBodySpecs( $celestialBodyEntity->getType() );
    $minSpecs = $galaxyModel->getMinCelestialBodySpecs( $celestialBodyEntity->getType() );
    
    foreach ($this->celestialBodySpecialtyRepository->findAll() as $specialty)
    {
      $assignSpecialty = rand( 1, self::SPECIALTY_LIKELIHOOD ) ===  1;
      if ($assignSpecialty) {
        $celestialBodyEntity->getSpecialties()->add( $specialty );
      }
    }
    
    $effects = $celestialBodyEntity->getSpecs()->getEffects();
    $minEffects = $minSpecs->getEffects();
    $maxEffects = $maxSpecs->getEffects();
    
    $effects->setBuildingCost($this->rand($minEffects->getBuildingCost(),$maxEffects->getBuildingCost()));
    $effects->setBuildingTime($this->rand($minEffects->getBuildingTime(),$maxEffects->getBuildingTime()));
    $effects->setFleetScannerRange($this->rand($minEffects->getFleetScannerRange(),$maxEffects->getFleetScannerRange()));
    $effects->setResearchPoints($this->rand($minEffects->getResearchPoints(),$maxEffects->getResearchPoints()));
    $effects->setTaxes($this->rand($minEffects->getTaxes(),$maxEffects->getTaxes()));
    
    $specs = $celestialBodyEntity->getSpecs();
    $specs->setGravity($this->rand($minSpecs->getGravity(),$maxSpecs->getGravity()));
    $specs->setLivingConditions($this->rand($minSpecs->getLivingConditions(),$maxSpecs->getLivingConditions()));
      
    $resourceDensity = $celestialBodyEntity->getSpecs()->getResourceDensity();
    $minResourceDensity = $minSpecs->getResourceDensity();
    $maxResourceDensity = $maxSpecs->getResourceDensity();
    
    $resourceDensity->setChemicals($this->rand($minResourceDensity->getChemicals(),$maxResourceDensity->getChemicals()));
    $resourceDensity->setIce($this->rand($minResourceDensity->getIce(),$maxResourceDensity->getIce()));
    $resourceDensity->setIron($this->rand($minResourceDensity->getIron(),$maxResourceDensity->getIron()));
  }
  
}
