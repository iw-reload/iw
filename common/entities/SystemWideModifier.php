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
   * Just a label for identifiying a system wide modifier in DB. Think of
   * "Blue nebula".
   * @var String
   * @Column(type="string")
   */
  private $label = '';
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
  private $specsModifier = null;
  /**
   * Unidirectional - Many SystemWideModifiers can imply many CelestialBodySpecialties
   * on the CelestialBodies in the system.
   * 
   * @var \Doctrine\Common\Collections\Collection
   * @ManyToMany(targetEntity="CelestialBodySpecialty", indexBy="type")
   */
  private $impliedSpecialties = null;
  
  public function __construct() {
    $this->specsModifier = new CelestialBodySpecs();
    $this->impliedSpecialties = new \Doctrine\Common\Collections\ArrayCollection();
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

  public function getNebulaColor() {
    return $this->nebulaColor;
  }

  public function setNebulaColor($nebulaColor) {
    $this->nebulaColor = (int)$nebulaColor;
  }

  public function getSpecsModifier() {
    return $this->specsModifier;
  }
  
  public function getImpliedSpecialities() {
    return $this->impliedSpecialties;
  }
}
