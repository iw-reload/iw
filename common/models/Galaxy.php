<?php

namespace common\models;

use common\entities\CelestialBody as CelestialBodyEntity;
use common\entities\CelestialBodySpecs;
use common\entities\Galaxy as GalaxyEntity;


/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
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
    CelestialBodyEntity::DISCR_ASTEROID
    , CelestialBodyEntity::DISCR_GAS_GIANT
    , CelestialBodyEntity::DISCR_ICE_GIANT
    , CelestialBodyEntity::DISCR_TERRESTRIAL_PLANET
    , CelestialBodyEntity::DISCR_VOID
  ];
  /**
   * @var array
   */
  private $normalGalaxyCelestialBodyTypes = [
    CelestialBodyEntity::DISCR_ASTEROID
    , CelestialBodyEntity::DISCR_ELECTRICITY_STORM
    , CelestialBodyEntity::DISCR_GAS_GIANT
    , CelestialBodyEntity::DISCR_GRAVIMETRIC_ANOMALY
    , CelestialBodyEntity::DISCR_ICE_GIANT
    , CelestialBodyEntity::DISCR_ION_STORM
    , CelestialBodyEntity::DISCR_SPATIAL_DISTORTION
    , CelestialBodyEntity::DISCR_TERRESTRIAL_PLANET
    , CelestialBodyEntity::DISCR_VOID
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
