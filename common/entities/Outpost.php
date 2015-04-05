<?php

namespace common\entities;

/**
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"celestialBody_id"}),
 *  }
 * )
 * @Entity
 * @author ben
 */
class Outpost
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var string
   * @Column(type = "string")
   */
  private $name = '';
  /**
   * Bidirectional - One-To-One (OWNING SIDE)
   * @var CelestialBody
   * @OneToOne(targetEntity="CelestialBody", mappedBy="outpost")
   * @JoinColumn(nullable=false,onDelete="CASCADE")
   */
  private $celestialBody = null;
  
  public function __construct()
  {
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = (string)$name;
  }

  public function getCelestialBody() {
    return $this->celestialBody;
  }
  
  public function setCelestialBody( CelestialBody $celestialBody, $sync=true )
  {
    $this->celestialBody = $celestialBody;
    
    if ($sync) {
      $celestialBody->setOutpost( $this, false );
    }
  }
}
