<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class GalaxyWideModifier
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var string
   * @Column(type = "string")
   */
  private $celestialBodyType;
  /**
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $specsModifier;
  
  public function __construct() {
    $this->specsModifier = new CelestialBodySpecs();
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getCelestialBodyType() {
    return $this->celestialBodyType;
  }

  public function setCelestialBodyType($celestialBodyType) {
    $this->celestialBodyType = (string)$celestialBodyType;
  }

  public function getSpecsModifier(CelestialBodySpecs $specsModifier) {
    $this->specsModifier = $specsModifier;
  }  
}
