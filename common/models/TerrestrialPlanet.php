<?php

namespace common\models;

use common\entities\TerrestrialPlanet as TerrestrialPlanetEntity;
use common\entityRepositories\CelestialBodySpecialty as CelestialBodySpecialtyRepository;

/**
 * 
 */
class TerrestrialPlanet extends CelestialBody
{
  /**
   * @var TerrestrialPlanetEntity
   */   
  private $terrestrialPlanetEntity = null;
  /**
   * @var CelestialBodySpecialtyRepository
   */
  private $celestialBodySpecialtyRepository = null;
  
  public function __construct
    ( TerrestrialPlanetEntity $entity
    , CelestialBodySpecialtyRepository $celestialBodySpecialtyRepository
    ) {
    parent::__construct( $entity );
    $this->celestialBodySpecialtyRepository = $celestialBodySpecialtyRepository;
    $this->terrestrialPlanetEntity = $entity;
  }
  
  /**
   * Resets the celestial body attributes to the defaults for new players.
   */
  public function reset()
  {
    // TODO Check modifiers of system and galaxy? Throw if there are any?
    //      Basically, we only want to reset planets for new players.
    //      Those shouldn't be in chaos galaxies or systems with nebula.
    
    $specs = $this->terrestrialPlanetEntity->getSpecs();
    
    $specs->setGravity( 1.0 );
    $specs->setLivingConditions( 1.0 );

    $specs->getResourceDensity()->setChemicals( 1.0 );
    $specs->getResourceDensity()->setIce( 0.3 );
    $specs->getResourceDensity()->setIron( 1.0 );
    
    $specs->getEffects()->setBuildingCost( 1.0 );
    $specs->getEffects()->setBuildingTime( 1.0 );
    $specs->getEffects()->setFleetScannerRange( 1.0 );
    $specs->getEffects()->setResearchPoints( 1.0 );
    $specs->getEffects()->setTaxes( 1.0 );

    $specialties = $this->terrestrialPlanetEntity->getSpecialties();
    
    $specialties->clear();
    $specialties->add( $this->celestialBodySpecialtyRepository->getMoon() );
  }
}
