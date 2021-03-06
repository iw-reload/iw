<?php

namespace common\entities;

/**
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"galaxy_id","number"}),
 *  }
 * )
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class System
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
   * @var SystemWideModifier
   * @ManyToOne(targetEntity="SystemWideModifier")
   * @JoinColumn(onDelete="SET NULL")
   */
  private $modifier = null;
  /**
   * Bidirectional - Many Systems make up one Galaxy (OWNING SIDE)
   * 
   * @var Galaxy
   * @ManyToOne(targetEntity="Galaxy", inversedBy="systems")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $galaxy = null;
  /**
   * Bidirectional - One-To-Many (INVERSE SIDE)
   * @var \Doctrine\Common\Collections\Collection
   * @OneToMany(targetEntity="CelestialBody", mappedBy="system", indexBy="number")
   */
  private $celestialBodies = null;
  
  public function __construct()
  {
    $this->galaxy = new Galaxy();
    $this->celestialBodies = new \Doctrine\Common\Collections\ArrayCollection();
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

  public function hasModifier() {
    return $this->modifier instanceof SystemWideModifier;
  }
  
  public function getModifier() {
    return $this->modifier;
  }
  
  public function setModifier(SystemWideModifier $modifier) {
    return $this->modifier = $modifier;
  }
  
  public function getGalaxy() {
    return $this->galaxy;
  }
  
  public function setGalaxy( Galaxy $galaxy, $sync=true )
  {
    $this->galaxy = $galaxy;
    
    if ($sync) {
      $galaxy->addSystem( $this, false );
    }
  }
  
  /**
   * @param \common\entities\System $celestialBody
   * @param bool $sync
   */
  public function addCelestialBody( CelestialBody $celestialBody, $sync=true )
  {
    $this->celestialBodies->set( $celestialBody->getNumber(), $celestialBody );
    
    if ($sync) {
      $celestialBody->setSystem( $this, false );
    }
  }
  
  public function countCelestialBodies() {
    return $this->celestialBodies->count();
  }
 
  public function getCelestialBodyNumbers() {
    return $this->celestialBodies->getKeys();
  }
 
  public function hasCelestialBody( $number ) {
    return $this->celestialBodies->containsKey( $number );
  }
 
  public function getCelestialBody( $number ) {
    return $this->celestialBodies->get( $number );
  }
 
  public function getAllCelestialBodies() {
    return $this->celestialBodies->toArray();
  }
  
}
