<?php

namespace common\entities;

/**
 * Description of CelestialBody
 * 
 * @Entity(repositoryClass="common\entityRepositories\CelestialBody")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="smallint")
 * @DiscriminatorMap({
 *    0 = "Void"
 *  , 1 = "TerrestrialPlanet"
 *  , 2 = "IceGiant"
 *  , 3 = "GasGiant"
 *  , 4 = "Asteroid"
 *  , 5 = "ElectricityStorm"
 *  , 6 = "IonStorm"
 *  , 7 = "SpatialDistortion"
 *  , 8 = "GravimetricAnomaly"
 * })
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"system_id","number"}),
 *  }
 * )
 * @author ben
 */
abstract class CelestialBody
{
  const DISCR_VOID = 0;
  const DISCR_TERRESTRIAL_PLANET = 1;
  const DISCR_ICE_GIANT = 2;
  const DISCR_GAS_GIANT = 3;
  const DISCR_ASTEROID = 4;
  const DISCR_ELECTRICITY_STORM = 5;
  const DISCR_ION_STORM = 6;
  const DISCR_SPATIAL_DISTORTION = 7;
  const DISCR_GRAVIMETRIC_ANOMALY = 8;
  
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
   * Bidirectional - One-To-ZeroOrOne (INVERSE SIDE)
   * @var Outpost|null
   * @OneToOne(targetEntity="Outpost", mappedBy="celestialBody")
   */
  private $outpost = null;
  /**
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  protected $specs = null;
  /**
   * Unidirectional - Many CelestialBodies can have many CelestialBodySpecialties
   * 
   * @var \Doctrine\Common\Collections\Collection
   * @ManyToMany(targetEntity="CelestialBodySpecialty", indexBy="type")
   */
  private $specialties = null;
  /**
   * Bidirectional - Many CelestialBodies make uo one System (OWNING SIDE)
   * 
   * @var System
   * @ManyToOne(targetEntity="System", inversedBy="celestialBodies")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $system = null;
  
  public function __construct() {
    $this->specs = new CelestialBodySpecs();
    $this->specialties = new \Doctrine\Common\Collections\ArrayCollection();
    $this->system = new System;
  }
  
  abstract public function getType();

  public function getId() {
    return $this->id;
  }
  
  public function getNumber() {
    return $this->number;
  }

  public function setNumber($number) {
    $this->number = (int)$number;
  }

  public function hasOutpost() {
    return $this->outpost instanceof Outpost;
  }

  public function getOutpost() {
    return $this->outpost;
  }

  public function setOutpost(Outpost $outpost, $sync=true )
  {
    $this->outpost = $outpost;
    
    if ($sync) {
      $outpost->setCelestialBody( $this, false );
    }
  }

  public function getSpecs() {
    return $this->specs;
  }  
  
  public function getSpecialties() {
    return $this->specialties;
  }
  
  public function getSystem() {
    return $this->system;
  }
  
  public function setSystem( System $system, $sync=true )
  {
    $this->system = $system;
    
    if ($sync) {
      $system->addCelestialBody( $this, false );
    }
  }
}
