<?php

namespace common\entities;

/**
 * @Entity
 * @Table(
 *  uniqueConstraints={
 *    @UniqueConstraint(columns={"galaxy_id","celestialBodyType"})
 *  }
 * )
 * @author ben
 */
class GalaxyWideModifier
{
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * Just a label for identifiying a galaxy wide modifier in DB. Think of
   * "Asteroids in Chaos-Galaxies".
   * @var String
   * @Column(type="string")
   */
  private $label = '';
  /**
   * The celestial body type this galaxy wide modifier refers to.
   * @var int
   * @Column(type = "smallint")
   */
  private $celestialBodyType;
  /**
   * Bidirectional - Many (but at most one for every celestialBodyType)
   * GalaxyWideModifiers can apply to one Galaxy (OWNING SIDE)
   * 
   * @var Galaxy
   * @ManyToOne(targetEntity="Galaxy", inversedBy="modifiers")
   * @JoinColumn(onDelete="CASCADE")
   */
  private $galaxy = null;
  /**
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  private $specsModifier = null;
  
  public function __construct() {
    $this->specsModifier = new CelestialBodySpecs();
  }
  
  public function getCelestialBodyType() {
    return $this->celestialBodyType;
  }

  public function getId() {
    return $this->id;
  }
  
  public function getLabel() {
    return $this->label;
  }

  public function getSpecsModifier() {
    return $this->specsModifier;
  }  

  public function setCelestialBodyType($celestialBodyType) {
    $this->celestialBodyType = $celestialBodyType;
  }
  
  public function setGalaxy(Galaxy $galaxy, $sync=true)
  {
    $this->galaxy = $galaxy;
    
    if ($sync) {
      $galaxy->addModifier( $this, false );
    }
  }
  
  public function setLabel($label) {
    $this->label = (string)$label;
  }
}
