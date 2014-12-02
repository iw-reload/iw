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
   * Smallest CelestialBody Specs around.
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $minSpecs;
  /**
   * Biggest CelestialBody Specs around.
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $maxSpecs;
  
  public function __construct() {
    $this->defaultSpecs = new CelestialBodySpecs();
    $this->minSpecs = new CelestialBodySpecs();
    $this->maxSpecs = new CelestialBodySpecs();
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

  public function getMinSpecs() {
    return $this->minSpecs;
  }

  public function getMaxSpecs() {
    return $this->maxSpecs;
  }
}
