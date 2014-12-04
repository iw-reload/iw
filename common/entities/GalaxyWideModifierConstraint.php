<?php

namespace common\entities;

/**
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"galaxy_id","celestialBodyType"}),
 *    @UniqueConstraint(columns={"galaxy_id","modifier_id"})
 *  }
 * )
 * @Entity
 * @author ben
 */
class GalaxyWideModifierConstraint
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * Bidirectional - Many (but at most one for every celestialBodyType)
   * GalaxyWideModifierConstraints can apply to one Galaxy (OWNING SIDE)
   * 
   * @var Galaxy
   * @ManyToOne(targetEntity="Galaxy", inversedBy="modifiersByCelestialBodyType")
   * @JoinColumn(onDelete="CASCADE")
   */
  private $galaxy = null;
  /**
   * @var string
   * @Column(type="string")
   */
  private $celestialBodyType = '';
  /**
   * Unidirectional - Many GalaxyWideModifierConstraints can refer to one
   * GalaxyWideModifier.
   * 
   * @var GalaxyWideModifier
   * @ManyToOne(targetEntity="GalaxyWideModifier")
   * @JoinColumn(onDelete="CASCADE")
   */
  private $modifier = null;
  
  public function __construct()
  {
    $this->galaxy = new Galaxy();
    $this->modifier = new GalaxyWideModifier();
  }
  
  public function getId() {
    return $this->id;
  }

  public function getGalaxy() {
    return $this->galaxy;
  }

  public function getCelestialBodyType() {
    return $this->celestialBodyType;
  }

  public function getModifier() {
    return $this->modifier;
  }

  public function setModifierForCelestialBodyTypeInGalaxy( GalaxyWideModifier $modifier, $celestialBodyType, Galaxy $galaxy, $sync=true )
  {
    $this->modifier = $modifier;
    $this->celestialBodyType = (string)$celestialBodyType;
    $this->galaxy = $galaxy;
    
    if ($sync) {
      $galaxy->modifierRegistered( $this, false );
    }
  }
}
