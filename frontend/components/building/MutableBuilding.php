<?php

namespace frontend\components\building;

use frontend\models\Building;
use frontend\behaviors\ShelterCapacityBehavior;

/**
 * Helper class allowing frontend\components\building\BuildingComponent to
 * intialize \frontend\models\Building instances.
 */
class MutableBuilding extends Building
{
  private $behaviorsEnsured = false;
  
  /**
   * @return array the behavior configurations for all building groups.
   */
  private function buildingGroupBehaviors() {
    return [
      'shelters' => [
        ShelterCapacityBehavior::className(),
      ],
    ];
  }

  /**
   * @return array the behavior configurations for the specified building group
   */
  private function getBuildingGroupBehaviors( $buildingGroup )
  {
    $buildingGroupBehaviors = $this->buildingGroupBehaviors();
    return array_key_exists( $buildingGroup, $buildingGroupBehaviors )
      ? $buildingGroupBehaviors[$buildingGroup]
      : [];
  }
  
  public function ensureBehaviors()
  {
    parent::ensureBehaviors();
    
    if (!$this->behaviorsEnsured)
    {
      $this->behaviorsEnsured = true;
      $buildingGroupBehaviors = $this->getBuildingGroupBehaviors( $this->getGroup() );
      $this->attachBehaviors( $buildingGroupBehaviors );
    }
  }
  
  public function setId($id) {
    $this->id = (int)$id;
  }

  public function setGroup($group) {
    $this->group = (string)$group;
  }

  public function setName($name) {
    $this->name = (string)$name;
  }

  public function setImage($image) {
    $this->image = $this->createUrl( $image );
  }

  public function setDescription($description) {
    $this->description = (string)$description;
  }

  public function setCostIron($costIron) {
    $this->costIron = $costIron;
  }

  public function setCostSteel($costSteel) {
    $this->costSteel = (int)$costSteel;
  }

  public function setCostChemicals($costChemicals) {
    $this->costChemicals = (int)$costChemicals;
  }

  public function setCostVv4a($costVv4a) {
    $this->costVv4a = (int)$costVv4a;
  }

  public function setCostIce($costIce) {
    $this->costIce = (int)$costIce;
  }

  public function setCostWater($costWater) {
    $this->costWater = (int)$costWater;
  }

  public function setCostEnergy($costEnergy) {
    $this->costEnergy = (int)$costEnergy;
  }

  public function setCostPeople($costPeople) {
    $this->costPeople = (int)$costPeople;
  }

  public function setCostCredits($costCredits) {
    $this->costCredits = (int)$costCredits;
  }

  public function setCostTime($costTime) {
    $this->costTime = new \DateInterval( $costTime );
  }

  public function setBalanceIron($balanceIron) {
    $this->balanceIron = (int)$balanceIron;
  }

  public function setBalanceSteel($balanceSteel) {
    $this->balanceSteel = (int)$balanceSteel;
  }

  public function setBalanceChemicals($balanceChemicals) {
    $this->balanceChemicals = (int)$balanceChemicals;
  }

  public function setBalanceVv4a($balanceVv4a) {
    $this->balanceVv4a = (int)$balanceVv4a;
  }

  public function setBalanceIce($balanceIce) {
    $this->balanceIce = (int)$balanceIce;
  }

  public function setBalanceWater($balanceWater) {
    $this->balanceWater = (int)$balanceWater;
  }

  public function setBalanceEnergy($balanceEnergy) {
    $this->balanceEnergy = (int)$balanceEnergy;
  }

  public function setBalancePeople($balancePeople) {
    $this->balancePeople = (int)$balancePeople;
  }

  public function setBalanceCredits($balanceCredits) {
    $this->balanceCredits = (int)$balanceCredits;
  }

  public function setBalanceSatisfaction($balanceSatisfaction) {
    $this->balanceSatisfaction = (float)$balanceSatisfaction;
  }

  public function setStorageChemicals($storageChemicals) {
    $this->storageChemicals = (int)$storageChemicals;
  }

  public function setStorageIceAndWater($storageIceAndWater) {
    $this->storageIceAndWater = (int)$storageIceAndWater;
  }

  public function setStorageEnergy($storageEnergy) {
    $this->storageEnergy = (int)$storageEnergy;
  }

  public function setShelterIron($shelterIron) {
    $this->shelterIron = (int)$shelterIron;
  }

  public function setShelterSteel($shelterSteel) {
    $this->shelterSteel = (int)$shelterSteel;
  }

  public function setShelterChemicals($shelterChemicals) {
    $this->shelterChemicals = (int)$shelterChemicals;
  }

  public function setShelterVv4a($shelterVv4a) {
    $this->shelterVv4a = (int)$shelterVv4a;
  }

  public function setShelterIceAndWater($shelterIceAndWater) {
    $this->shelterIceAndWater = (int)$shelterIceAndWater;
  }

  public function setShelterEnergy($shelterEnergy) {
    $this->shelterEnergy = (int)$shelterEnergy;
  }

  public function setShelterPeople($shelterPeople) {
    $this->shelterPeople = (int)$shelterPeople;
  }

  public function setHighscorePoints($highscorePoints) {
    $this->highscorePoints = (int)$highscorePoints;
  }
  
  // TODO create a component/ helper.
  // Should detect
  // - absolute URLs
  // - relative URLs (configurable relative to what)
  // maybe 
  // - alias prefixed URLs
  // - URL routes
  private function createUrl( $value )
  {
    $url = 'http://placehold.it/50x50';
    
    if (\filter_var($value,\FILTER_VALIDATE_URL)) {
      $url = $value;
    }
    
    return $url;
  }
}