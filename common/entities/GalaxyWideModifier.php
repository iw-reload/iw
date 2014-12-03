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
   * Just a label for identifiying a galaxy wide modifier in DB. Think of
   * "Asteroids in Chaos-Galaxies".
   * @var String
   * @Column(type="string")
   */
  private $label = '';
  /**
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $specsModifier = null;
  
  public function __construct() {
    $this->specsModifier = new CelestialBodySpecs();
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getLabel() {
    return $this->label;
  }

  public function setLabel($label) {
    $this->label = (string)$label;
  }
    
  public function getSpecsModifier() {
    return $this->specsModifier;
  }  
}
