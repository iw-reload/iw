<?php

namespace frontend\models;

use frontend\interfaces\ShelterInterface;
use frontend\objects\Resources;
use yii\base\Component;

/**
 * Read-only representation of a building.
 * 
 * Since the frontend application is not meant to modify the definition of
 * buildings, we use this simple wrapper class to access building properties.
 * 
 * The frontend\components\Building component is responsible for creating
 * instances of this class and to initialize them with their properties.
 *
 * @author ben
 */
abstract class Building extends Component
                        implements ShelterInterface
{
  /**
   * @var int Id of the building
   */
  protected $id = 0;
  /**
   * @var string
   */
  protected $group = '';
  /**
   * @var string
   */
  protected $name = '';
  /**
   * @var string
   */
  protected $image = '';
  /**
   * @var string
   */
  protected $description = '';
  /**
   * @var int
   */
  protected $costIron = 0;
  /**
   * @var int
   */
  protected $costSteel = 0;
  /**
   * @var int
   */
  protected $costChemicals = 0;
  /**
   * @var int
   */
  protected $costVv4a = 0;
  /**
   * @var int
   */
  protected $costIce = 0;
  /**
   * @var int
   */
  protected $costWater = 0;
  /**
   * @var int
   */
  protected $costEnergy = 0;
  /**
   * @var int
   */
  protected $costPeople = 0;
  /**
   * @var int
   */
  protected $costCredits = 0;
  /**
   * @var DateInterval
   */
  protected $costTime = null;
  /**
   * @var int
   */
  protected $balanceIron = 0;
  /**
   * @var int
   */
  protected $balanceSteel = 0;
  /**
   * @var int
   */
  protected $balanceChemicals = 0;
  /**
   * @var int
   */
  protected $balanceVv4a = 0;
  /**
   * @var int
   */
  protected $balanceIce = 0;
  /**
   * @var int
   */
  protected $balanceWater = 0;
  /**
   * @var int
   */
  protected $balanceEnergy = 0;
  /**
   * @var int
   */
  protected $balancePeople = 0;
  /**
   * @var int
   */
  protected $balanceCredits = 0;
  /**
   * @var float
   */
  protected $balanceSatisfaction = 0.0;
  /**
   * @var int
   */
  protected $storageChemicals = 0;
  /**
   * @var int
   */
  protected $storageIceAndWater = 0;
  /**
   * @var int
   */
  protected $storageEnergy = 0;
  /**
   * @var int
   */
  protected $shelterIron = 0;
  /**
   * @var int
   */
  protected $shelterSteel = 0;
  /**
   * @var int
   */
  protected $shelterChemicals = 0;
  /**
   * @var int
   */
  protected $shelterVv4a = 0;
  /**
   * @var int
   */
  protected $shelterIceAndWater = 0;
  /**
   * @var int
   */
  protected $shelterEnergy = 0;
  /**
   * @var int
   */
  protected $shelterPeople = 0;
  /**
   * @var int
   */
  protected $highscorePoints = 0;
  
  public function getId() {
    return $this->id;
  }

  public function getGroup() {
    return $this->group;
  }

  public function getName() {
    return $this->name;
  }

  public function getImage() {
    return $this->image;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getCostIron() {
    return $this->costIron;
  }

  public function getCostSteel() {
    return $this->costSteel;
  }

  public function getCostChemicals() {
    return $this->costChemicals;
  }

  public function getCostVv4a() {
    return $this->costVv4a;
  }

  public function getCostIce() {
    return $this->costIce;
  }

  public function getCostWater() {
    return $this->costWater;
  }

  public function getCostEnergy() {
    return $this->costEnergy;
  }

  public function getCostPeople() {
    return $this->costPeople;
  }

  public function getCostCredits() {
    return $this->costCredits;
  }

  public function getCostTime() {
    return $this->costTime;
  }

  public function getBalanceIron() {
    return $this->balanceIron;
  }

  public function getBalanceSteel() {
    return $this->balanceSteel;
  }

  public function getBalanceChemicals() {
    return $this->balanceChemicals;
  }

  public function getBalanceVv4a() {
    return $this->balanceVv4a;
  }

  public function getBalanceIce() {
    return $this->balanceIce;
  }

  public function getBalanceWater() {
    return $this->balanceWater;
  }

  public function getBalanceEnergy() {
    return $this->balanceEnergy;
  }

  public function getBalancePeople() {
    return $this->balancePeople;
  }

  public function getBalanceCredits() {
    return $this->balanceCredits;
  }

  public function getBalanceSatisfaction() {
    return $this->balanceSatisfaction;
  }

  public function getStorageChemicals() {
    return $this->storageChemicals;
  }

  public function getStorageIceAndWater() {
    return $this->storageIceAndWater;
  }

  public function getStorageEnergy() {
    return $this->storageEnergy;
  }

  public function getShelterIron() {
    return $this->shelterIron;
  }

  public function getShelterSteel() {
    return $this->shelterSteel;
  }

  public function getShelterChemicals() {
    return $this->shelterChemicals;
  }

  public function getShelterVv4a() {
    return $this->shelterVv4a;
  }

  public function getShelterIceAndWater() {
    return $this->shelterIceAndWater;
  }

  public function getShelterEnergy() {
    return $this->shelterEnergy;
  }

  public function getShelterPeople() {
    return $this->shelterPeople;
  }

  public function getHighscorePoints() {
    return $this->highscorePoints;
  }
  
  public function getShelterCapacity() {
    return new Resources([
      'iron' => $this->getShelterIron(),
      'steel' => $this->getShelterSteel(),
      'chemicals' => $this->getShelterChemicals(),
      'vv4a' => $this->getShelterVv4a(),
      'population' => $this->getShelterPeople(),
      'ice' => $this->getShelterIceAndWater(),
      'water' => $this->getShelterIceAndWater(),
      'energy' => $this->getShelterEnergy(),
      'credits' => 0,
    ]);
  }
  
  public function getCost() {
    return new Resources([
      'iron' => $this->getCostIron(),
      'steel' => $this->getCostSteel(),
      'chemicals' => $this->getCostChemicals(),
      'vv4a' => $this->getCostVv4a(),
      'population' => $this->getCostPeople(),
      'ice' => $this->getCostIce(),
      'water' => $this->getCostWater(),
      'energy' => $this->getCostEnergy(),
      'credits' => $this->getCostCredits(),
    ]);
  }
  
  public function getBalance() {
    return new Resources([
      'iron' => $this->getBalanceIron(),
      'steel' => $this->getBalanceSteel(),
      'chemicals' => $this->getBalanceChemicals(),
      'vv4a' => $this->getBalanceVv4a(),
      'population' => $this->getBalancePeople(),
      'ice' => $this->getBalanceIce(),
      'water' => $this->getBalanceWater(),
      'energy' => $this->getBalanceEnergy(),
      'credits' => $this->getBalanceCredits(),
    ]);
  }
  
  public function getEffects() {
    return [
      'satisfaction' => $this->getBalanceSatisfaction(),
    ];
  }
}
