<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Universe
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
  private $minSystems = 0;
  /**
   * @var int
   * @Column(type = "smallint", options={"unsigned":true})
   */
  private $maxSystems = 0;
  /**
   * @var int
   * @Column(type = "smallint", options={"unsigned":true})
   */
  private $minCelestialBodies = 0;
  /**
   * @var int
   * @Column(type = "smallint", options={"unsigned":true})
   */
  private $maxCelestialBodies = 0;
  /**
   * CelestialBody Specs for new player's home worlds.
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $defaultSpecs;
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="CelestialBodyTypeSpecs", mappedBy="universe", indexBy="celestialBodyType")
   */
  private $celestialBodyTypeSpecs = null;
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="Galaxy", mappedBy="universe", indexBy="number")
   */
  private $galaxies = null;
  
  public function __construct() {
    $this->defaultSpecs = new CelestialBodySpecs();
    $this->celestialBodyTypeSpecs = new \Doctrine\Common\Collections\ArrayCollection();
    $this->galaxies = new \Doctrine\Common\Collections\ArrayCollection();
  }
  
  public function getId() {
    return $this->id;
  }

  public function getMinSystems() {
    return $this->minSystems;
  }

  public function getMaxSystems() {
    return $this->maxSystems;
  }

  public function getMinCelestialBodies() {
    return $this->minCelestialBodies;
  }

  public function getMaxCelestialBodies() {
    return $this->maxCelestialBodies;
  }

  public function setMinSystems($minSystems) {
    $this->minSystems = (int)$minSystems;
  }

  public function setMaxSystems($maxSystems) {
    $this->maxSystems = (int)$maxSystems;
  }

  public function setMinCelestialBodies($minCelestialBodies) {
    $this->minCelestialBodies = (int)$minCelestialBodies;
  }

  public function setMaxCelestialBodies($maxCelestialBodies) {
    $this->maxCelestialBodies = (int)$maxCelestialBodies;
  }
  
  public function getDefaultSpecs() {
    return $this->defaultSpecs;
  }

  public function addCelestialBodyTypeSpecs(CelestialBodyTypeSpecs $celestialBodyTypeSpecs, $sync=true)
  {
    $this->celestialBodyTypeSpecs->set( $celestialBodyTypeSpecs->getCelestialBodyType(), $celestialBodyTypeSpecs );
    
    if ($sync) {
      $celestialBodyTypeSpecs->setUniverse( $this, false );
    }
  }
  
  /**
   * @param int $celestialBodyType
   * @return CelestialBodyTypeSpecs
   */
  public function getCelestialBodyTypeSpecs( $celestialBodyType ) {
    return $this->celestialBodyTypeSpecs->get( $celestialBodyType );
  }
  
  /**
   * @param \common\entities\Galaxy $galaxy
   * @param bool $sync
   */
  public function addGalaxy(Galaxy $galaxy, $sync=true)
  {
    $this->galaxies->set( $galaxy->getNumber(), $galaxy );
    
    if ($sync) {
      $galaxy->setUniverse( $this, false );
    }
  }
  
  public function countGalaxies() {
    return $this->galaxies->count();
  }
 
  public function getGalaxyNumbers() {
    return $this->galaxies->getKeys();
  }
 
  public function hasGalaxy( $number ) {
    return $this->galaxies->containsKey( $number );
  }
 
  public function getGalaxy( $number ) {
    return $this->galaxies->get( $number );
  }
 
  public function getAllGalaxies() {
    return $this->galaxies->toArray();
  }
}
