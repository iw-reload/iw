<?php

namespace common\entities;

/**
 * @Entity(repositoryClass="common\entityRepositories\CelestialBodySpecialty")
 * @author ben
 */
class CelestialBodySpecialty
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
  private $type;
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

  public function getType() {
    return $this->type;
  }

  public function setType($type) {
    $this->type = (string)$type;
  }
  
  public function getSpecsModifier(CelestialBodySpecs $specsModifier) {
    $this->specsModifier = $specsModifier;
  }  
}
