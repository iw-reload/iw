<?php

namespace common\entities;

/**
 * @Entity(repositoryClass="common\entityRepositories\CelestialBodySpecialty")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="smallint")
 * @DiscriminatorMap({
 *     0 = "Moon"
 *  ,  1 = "OldRuins"
 *  ,  2 = "AsteroidBelt"
 *  ,  3 = "InstableCore"
 *  ,  4 = "Gold"
 *  ,  5 = "NaturalWell"
 *  ,  6 = "PlanetaryRing"
 *  ,  7 = "Radioative"
 *  ,  8 = "Toxic"
 *  ,  9 = "Natives"
 *  , 10 = "FewResources"
 * })
 * @author ben
 */
abstract class CelestialBodySpecialty
{
  const DISCR_MOON = 0;
  const DISCR_OLD_RUINS = 1;
  const DISCR_ASTEROID_BELT = 2;
  const DISCR_INSTABLE_CORE = 3;
  const DISCR_GOLD = 4;
  const DISCR_NATURAL_WELL = 5;
  const DISCR_PLANETARY_RING = 6;
  const DISCR_RADIOACTIVE = 7;
  const DISCR_TOXIC = 8;
  const DISCR_NATIVES = 9;
  const DISCR_FEW_RESOURCES = 10;
  
  /**
   * @var int
   * @Id @Column(type="integer")
   * @GeneratedValue
   */
  private $id;
  /**
   * @var CelestialBodySpecs
   * @Embedded(class = "CelestialBodySpecs")
   */
  protected $specsModifier;
  
  public function __construct() {
    $this->specsModifier = new CelestialBodySpecs();
  }

  public function getId() {
    return $this->id;
  }

  abstract public function getType();
  
  public function getSpecsModifier() {
    return $this->specsModifier;
  }
}
