<?php

namespace common\entities;

/**
 * @Entity(repositoryClass="common\entityRepositories\CelestialBodySpecialty")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="smallint")
 * @DiscriminatorMap({
 *     0 = "\common\entities\celestialBodySpecialties\Moon"
 *  ,  1 = "\common\entities\celestialBodySpecialties\OldRuins"
 *  ,  2 = "\common\entities\celestialBodySpecialties\AsteroidBelt"
 *  ,  3 = "\common\entities\celestialBodySpecialties\InstableCore"
 *  ,  4 = "\common\entities\celestialBodySpecialties\Gold"
 *  ,  5 = "\common\entities\celestialBodySpecialties\NaturalWell"
 *  ,  6 = "\common\entities\celestialBodySpecialties\PlanetaryRing"
 *  ,  7 = "\common\entities\celestialBodySpecialties\Radioative"
 *  ,  8 = "\common\entities\celestialBodySpecialties\Toxic"
 *  ,  9 = "\common\entities\celestialBodySpecialties\Natives"
 *  , 10 = "\common\entities\celestialBodySpecialties\FewResources"
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
