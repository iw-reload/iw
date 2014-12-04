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
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="System", mappedBy="galaxy", indexBy="number")
   */
  private $systems = null;
  
  public function __construct() {
    $this->modifiersByCelestialBodyType = new \Doctrine\Common\Collections\ArrayCollection();
    $this->systems = new \Doctrine\Common\Collections\ArrayCollection();
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
   * @param \common\entities\GalaxyWideModifierConstraint $galaxyWideModifierConstraint
   * @param bool $sync
   */
  public function addModifier( GalaxyWideModifierConstraint $galaxyWideModifierConstraint, $sync=true )
  {
    $this->modifiersByCelestialBodyType->set(
      $galaxyWideModifierConstraint->getCelestialBodyType(),
      $galaxyWideModifierConstraint );
    
    if ($sync)
    {
      $galaxyWideModifierConstraint->setModifierForCelestialBodyTypeInGalaxy(
        $galaxyWideModifierConstraint->getModifier(),
        $galaxyWideModifierConstraint->getCelestialBodyType(),
        $this,
        false );
    }
  }
  
  /**
   * @param \common\entities\System $system
   * @param bool $sync
   */
  public function addSystem( System $system, $sync=true )
  {
    $this->systems->set( $system->getNumber(), $system );
    
    if ($sync) {
      $system->setGalaxy( $this, false );
    }
  }
  
  public function hasSystem( $number ) {
    return $this->systems->containsKey( $number );
  }
 
  public function getSystem( $number ) {
    return $this->systems->get( $number );
  }
 
  public function getSystemNumbers() {
    return $this->systems->getKeys();
  }
 
  public function getAllSystems() {
    return $this->systems->toArray();
  }
  
}
