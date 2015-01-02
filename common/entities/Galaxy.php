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
   * @OneToMany(targetEntity="GalaxyWideModifier", mappedBy="galaxy", indexBy="celestialBodyType")
   */
  private $modifiers = null;
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="System", mappedBy="galaxy", indexBy="number")
   */
  private $systems = null;
  /**
   * Bidirectional - Many Galaxies make up one Universe (OWNING SIDE)
   * 
   * @var Universe
   * @ManyToOne(targetEntity="Universe", inversedBy="galaxies")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $universe = null;
  
  public function __construct() {
    $this->modifiers = new \Doctrine\Common\Collections\ArrayCollection();
    $this->systems = new \Doctrine\Common\Collections\ArrayCollection();
    $this->universe = new Universe();
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
  
  public function getModifierKeys() {
    return $this->modifiers->getKeys();
  }
  
  public function hasModifier($celestialBodyType) {
    return $this->modifiers->containsKey( $celestialBodyType );
  }
  
  public function getModifier($celestialBodyType) {
    return $this->modifiers->get( $celestialBodyType );
  }
  
  /**
   * @param \common\entities\GalaxyWideModifier $galaxyWideModifier
   * @param bool $sync
   */
  public function addModifier( GalaxyWideModifier $galaxyWideModifier, $sync=true )
  {
    $this->modifiers->set( $galaxyWideModifier->getCelestialBodyType(), $galaxyWideModifier );
    
    if ($sync) {
      $galaxyWideModifier->setGalaxy($this, false);
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
  
  public function countSystems() {
    return $this->systems->count();
  }
 
  public function getSystemNumbers() {
    return $this->systems->getKeys();
  }
 
  public function hasSystem( $number ) {
    return $this->systems->containsKey( $number );
  }
 
  public function getSystem( $number ) {
    return $this->systems->get( $number );
  }
 
  public function getAllSystems() {
    return $this->systems->toArray();
  }
  
  public function getUniverse() {
    return $this->universe;
  }
  
  public function setUniverse( Universe $universe, $sync=true )
  {
    $this->universe = $universe;
    
    if ($sync) {
      $universe->addGalaxy( $this, false );
    }
  }
  
}
