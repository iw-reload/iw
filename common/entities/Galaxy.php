<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Galaxy
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
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="GalaxyWideModifierConstraint", mappedBy="galaxy", indexBy="celestialBodyType")
   */
  private $modifiersByCelestialBodyType = null;
  
  public function __construct() {
    $this->modifierConstraints = new \Doctrine\Common\Collections\ArrayCollection();
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
  
  public function hasModifier($celestialBodyType) {
    return $this->modifiersByCelestialBodyType->containsKey( $celestialBodyType );
  }
  
  public function getModifier($celestialBodyType)
  {
    /* @var $galaxyWideModifierConstraint GalaxyWideModifierConstraint */
    $galaxyWideModifierConstraint = $this->modifiersByCelestialBodyType->get( $celestialBodyType );
    return $galaxyWideModifierConstraint->getModifier();
  }
  
  /**
   * Don't call this. It is meant to be used only by GalaxyWideModifierConstraint
   * to keep bidirectional associations in sync.
   * 
   * @param \common\entities\GalaxyWideModifierConstraint $galaxyWideModifierConstraint
   */
  public function modifierRegistered(GalaxyWideModifierConstraint $galaxyWideModifierConstraint)
  {
    $this->modifiersByCelestialBodyType->set(
      $galaxyWideModifierConstraint->getCelestialBodyType(),
      $galaxyWideModifierConstraint->getModifier() );
  }
}
