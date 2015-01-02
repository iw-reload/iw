<?php

namespace common\entities;

/**
 * @Embeddable
 * @author ben
 */
class CelestialBodySpecs
{
  /**
   * @var float
   * @Column(type = "float")
   */
  private $gravity = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $livingConditions = 0.0;
  /**
   * @var ResourceDensity
   * @Embedded(class = "ResourceDensity")
   */
  private $resourceDensity;
  /**
   * @var CelestialBodyEffects
   * @Embedded(class = "CelestialBodyEffects")
   */
  private $effects;
  
  public function __construct() {
    $this->resourceDensity = new ResourceDensity();
    $this->effects = new CelestialBodyEffects();
  }
  
  public function __clone() {
    $this->resourceDensity = clone $this->resourceDensity;
    $this->effects = clone $this->effects;
  }
  
  public function getGravity() {
    return $this->gravity;
  }

  public function getLivingConditions() {
    return $this->livingConditions;
  }

  public function setGravity($gravity) {
    $this->gravity = (float)$gravity;
  }

  public function setLivingConditions($livingConditions) {
    $this->livingConditions = (float)$livingConditions;
  }
  
  public function getResourceDensity() {
    return $this->resourceDensity;
  }  
  
  public function getEffects() {
    return $this->effects;
  }  
}
