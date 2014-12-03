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
  private $specs = null;
  /**
   * Unidirectional - Many CelestialBodies can have many CelestialBodySpecialties
   * 
   * @var \Doctrine\Common\Collections\Collection
   * @ManyToMany(targetEntity="CelestialBodySpecialty", indexBy="type")
   */
  private $specialties = null;
  
  public function __construct() {
    $this->specs = new CelestialBodySpecs();
    $this->specialties = new \Doctrine\Common\Collections\ArrayCollection();
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
  
  public function getSpecialties() {
    return $this->specialties;
  }  
}
