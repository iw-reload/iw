<?php

namespace common\entities;

/**
 * @Entity
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"universe_id","celestialBodyType"})
 *  }
 * )
 * @author ben
 */
class CelestialBodyTypeSpecs
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * The celestial body type these min and max specs refer to.
   * @var int
   * @Column(type = "smallint")
   */
  private $celestialBodyType;
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
  /**
   * Bidirectional - Many CelestialBodyTypeSpecs belong to one Universe (OWNING SIDE)
   * 
   * @var Universe
   * @ManyToOne(targetEntity="Universe", inversedBy="celestialBodyTypeSpecs")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $universe = null;
  
  public function __construct()
  {
    $this->maxSpecs = new CelestialBodySpecs();
    $this->minSpecs = new CelestialBodySpecs();
    $this->universe = new Universe();
  }
  
  public function __clone()
  {
    $this->maxSpecs = clone $this->maxSpecs;
    $this->minSpecs = clone $this->minSpecs;
  }
  
  public function getId() {
    return $this->id;
  }

  public function getCelestialBodyType() {
    return $this->celestialBodyType;
  }

  public function getMinSpecs() {
    return $this->minSpecs;
  }

  public function getMaxSpecs() {
    return $this->maxSpecs;
  }

  public function setCelestialBodyType($celestialBodyType) {
    $this->celestialBodyType = (int)$celestialBodyType;
  }

  public function setUniverse(Universe $universe, $sync=true)
  {
    $this->universe = $universe;
    
    if ($sync) {
      $universe->addCelestialBodyTypeSpecs( $this, false );
    }
  }
}
