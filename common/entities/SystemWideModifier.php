<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class SystemWideModifier
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * The actual color, RGBA.
   * @var int
   * @Column(type = "integer", options={"unsigned":true})
   */
  private $nebulaColor = 0;
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
  
  public function getNebulaColor() {
    return $this->nebulaColor;
  }

  public function setNebulaColor($nebulaColor) {
    $this->nebulaColor = (int)$nebulaColor;
  }

  public function getSpecsModifier(CelestialBodySpecs $specsModifier) {
    $this->specsModifier = $specsModifier;
  }  
}
