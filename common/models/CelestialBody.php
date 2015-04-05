<?php

namespace common\models;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * 
 */
class CelestialBody
{
  /**
   * @var CelestialBodyEntity
   */   
  private $celestialBodyEntity = null;

  public function __construct( CelestialBodyEntity $celestialBodyEntity ) {
    $this->celestialBodyEntity = $celestialBodyEntity;
  }
  
  /**
   * Returns a label for this celestial body. Can be used as text for links.
   */
  public function getLabel()
  {
    $system = $this->celestialBodyEntity->getSystem();
    $galaxy = $system->getGalaxy();
    return $this->createLabel(
      $galaxy->getNumber(),
      $system->getNumber(),
      $this->celestialBodyEntity->getNumber() );
  }
  
  /**
   * Returns a label for this celestial body. Can be used as text for links.
   */
  public static function createLabel( $gal, $sys, $pla ) {
    return "{$gal}:{$sys}:{$pla}";
  }
}
