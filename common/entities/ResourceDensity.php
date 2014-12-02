<?php

namespace common\entities;

/**
 * Description of ResourceDensity
 * 
 * @Embeddable
 * @author ben
 */
class ResourceDensity
{
  /**
   * @var float
   * @Column(type = "float")
   */
  private $iron = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $chemicals = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $ice = 0.0;
  
  public function getIron() {
    return $this->iron;
  }

  public function getChemicals() {
    return $this->chemicals;
  }

  public function getIce() {
    return $this->ice;
  }

  public function setIron($iron) {
    $this->iron = (float)$iron;
  }

  public function setChemicals($chemicals) {
    $this->chemicals = (float)$chemicals;
  }

  public function setIce($ice) {
    $this->ice = (float)$ice;
  }
}
