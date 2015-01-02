<?php

namespace common\models;

use common\entities\CelestialBody;
use common\entities\CelestialBodySpecs;
use common\entities\Galaxy as GalaxyEntity;


/**
 * @author ben
 */
class Galaxy
{
  // every 4th galaxy is a chaos galaxy
  const CHAOS_GALAXY_COUNTER = 4;
  const CHAOS_GALAXY_MIN_SPECS_MULTIPLIER = 0.5;
  const CHAOS_GALAXY_MAX_SPECS_MULTIPLIER = 2.0;
  
  /**
   * @var GalaxyEntity
   */   
  private $galaxyEntity = null;
  /**
   * @var array
   */
  private $chaosGalaxyCelestialBodyTypes = [
    CelestialBody::DISCR_ASTEROID
    , CelestialBody::DISCR_GAS_GIANT
    , CelestialBody::DISCR_ICE_GIANT
    , CelestialBody::DISCR_TERRESTRIAL_PLANET
    , CelestialBody::DISCR_VOID
  ];
  /**
   * @var array
   */
  private $normalGalaxyCelestialBodyTypes = [
    CelestialBody::DISCR_ASTEROID
    , CelestialBody::DISCR_ELECTRICITY_STORM
    , CelestialBody::DISCR_GAS_GIANT
    , CelestialBody::DISCR_GRAVIMETRIC_ANOMALY
    , CelestialBody::DISCR_ICE_GIANT
    , CelestialBody::DISCR_ION_STORM
    , CelestialBody::DISCR_SPATIAL_DISTORTION
    , CelestialBody::DISCR_TERRESTRIAL_PLANET
    , CelestialBody::DISCR_VOID
  ];
  
  public function __construct(GalaxyEntity $galaxyEntity) {
    $this->galaxyEntity = $galaxyEntity;
  }

  public function getAllowedCelestialBodyTypes() {
    return $this->isChaosGalaxy()
      ? $this->chaosGalaxyCelestialBodyTypes
      : $this->normalGalaxyCelestialBodyTypes;
  }
  
  /**
   * Might return a cloned object. Don't change it.
   * @param int $celestialBodyType
   * @return CelestialBodySpecs
   */
  public function getMaxCelestialBodySpecs( $celestialBodyType )
  {
    $maxSpecs = $this
      ->galaxyEntity
      ->getUniverse()
      ->getCelestialBodyTypeSpecs($celestialBodyType)
      ->getMaxSpecs();
        
    if ($this->isChaosGalaxy())
    {
      $maxSpecs = clone $maxSpecs;
      $this->multiplySpecs( $maxSpecs, 2.0 );
      return $maxSpecs;
    }
    else
    {
      return $maxSpecs;
    }
  }
  
  /**
   * Might return a cloned object. Don't change it.
   * @param int $celestialBodyType
   * @return CelestialBodySpecs
   */
  public function getMinCelestialBodySpecs( $celestialBodyType )
  {
    $minSpecs = $this
      ->galaxyEntity
      ->getUniverse()
      ->getCelestialBodyTypeSpecs($celestialBodyType)
      ->getMinSpecs();
        
    if ($this->isChaosGalaxy())
    {
      $minSpecs = clone $minSpecs;
      $this->multiplySpecs( $minSpecs, 0.5 );
      return $minSpecs;
    }
    else
    {
      return $minSpecs;
    }
  }
  
  private function isChaosGalaxy() {
    return $this->galaxyEntity->getNumber() % self::CHAOS_GALAXY_COUNTER === 0;
  }
  
  private static function multiplySpecs( CelestialBodySpecs $specs, $multiplier )
  {
    $specs->setGravity( $specs->getGravity() * $multiplier );
    $specs->setLivingConditions( $specs->getLivingConditions() * $multiplier );
    $specs->getResourceDensity()->setChemicals( $specs->getResourceDensity()->getChemicals() * $multiplier );
    $specs->getResourceDensity()->setIce( $specs->getResourceDensity()->getIce() * $multiplier );
    $specs->getResourceDensity()->setIron( $specs->getResourceDensity()->getIron() * $multiplier );
  }
}
