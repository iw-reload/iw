<?php

namespace common\entities;

/**
 * Describes how the CelestialBody influences various aspects of the game.
 * 
 * @Embeddable
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class CelestialBodyEffects
{
  /**
   * @var float
   * @Column(type = "float")
   */
  private $buildingCost = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $buildingTime = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $fleetScannerRange = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $researchPoints = 0.0;
  /**
   * @var float
   * @Column(type = "float")
   */
  private $taxes = 0.0;
  
  public function getBuildingCost() {
    return $this->buildingCost;
  }

  public function getBuildingTime() {
    return $this->buildingTime;
  }

  public function getFleetScannerRange() {
    return $this->fleetScannerRange;
  }

  public function getResearchPoints() {
    return $this->researchPoints;
  }

  public function getTaxes() {
    return $this->taxes;
  }

  public function setBuildingCost($buildingCost) {
    $this->buildingCost = (float)$buildingCost;
  }

  public function setBuildingTime($buildingTime) {
    $this->buildingTime = (float)$buildingTime;
  }

  public function setFleetScannerRange($fleetScannerRange) {
    $this->fleetScannerRange = (float)$fleetScannerRange;
  }

  public function setResearchPoints($researchPoints) {
    $this->researchPoints = (float)$researchPoints;
  }

  public function setTaxes($taxes) {
    $this->taxes = (float)$taxes;
  }


}
