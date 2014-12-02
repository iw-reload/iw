<?php

namespace common\entities;

/**
 * Description of CelestialBody
 *
 * @Entity
 * @author ben
 */
class CelestialBody
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var int
   * @Column(type = "smallint", options={"unsigned":true})
   */
  private $number = 0;
  /**
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $specs;
  
  public function __construct() {
    $this->specs = new CelestialBodySpecs();
  }

  public function getId() {
    return $this->id;
  }
  
  public function getNumber() {
    return $this->number;
  }

  public function setNumber($number) {
    $this->number = (int)$number;
  }

  public function getSpecs() {
    return $this->specs;
  }  
}
