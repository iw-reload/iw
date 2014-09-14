<?php

namespace frontend\objects;

use common\models\Base;
use frontend\interfaces\BuildingFinderInterface;
use frontend\objects\Resources;
use yii\base\Object;

/**
 * Calculates Production of a Base.
 *
 * @author ben
 */
class ProductionCalculator extends Object
{
  /**
   * @var BuildingFinderInterface
   */
  private $buildingFinder;
  /**
   * @var Base
   */
  private $base;
  private $balanceEnergy = 0.0;
  private $balanceIron = 0.0;
  private $balanceChemicals = 0.0;
  private $balanceIce = 0.0;
  private $balanceWater = 0.0;
  private $balanceSteel = 0.0;
  private $balanceVv4a = 0.0;

  
  public function __construct( BuildingFinderInterface $buildingFinder, $config = [] )
  {
    $this->buildingFinder = $buildingFinder;
    parent::__construct($config);
  }


  /**
   * @param \common\models\Base $base
   * @return \frontend\objects\Resources
   */
  public function calculateProduction( Base $base )
  {
    $this->base = $base;
    
    // The order in which these methods are called is important.
    // They all work on the instance's balanceXyz properties which influence
    // each other (for example, production of mines drops to 50% if no energy
    // is available. But before dropping production of base minerals, we reduce
    // production of higher level resources like steel and vv4a).
    
    // Energy is the foremost important resource to calculate.
    // The production of all other resources depends on a bases energy level.
    $this->initEnergyBalance();
    
    // Iron, Chemicals and Ice production depends a bases energy level.
    $this->initIronBalance();
    $this->initChemicalsBalance();
    $this->initIceBalance();
    
    // Water is the most important refined resource. Without water, people die.
    // So we will produce as much water as there is energy available.
    $this->initWaterBalance();
    
    // If the bases energy level is good enough, we produce steel from iron.
    // This will affect the balance of iron, steel and energy.
    $this->initSteelBalance();
    
    // If the bases energy level is still good enough, we produce vv4a from
    // steel. This will affect the balance of steel, vv4 and energy.
    $this->initVv4aBalance();
    
    return new Resources([
      'iron' => $this->balanceIron,
      'steel' => $this->balanceSteel,
      'chemicals' => $this->balanceChemicals,
      'vv4a' => $this->balanceVv4a,
      'ice' => $this->balanceIce,
      'water' => $this->balanceWater,
      'energy' => $this->balanceEnergy,
    ]);
  }
  
  
  /**
   * Initializes the energy balance of the base.
   * 
   * This step only takes into account the production and the upkeep of
   * buildings on the base.
   */
  private function initEnergyBalance()
  {
    $this->balanceEnergy = 0.0;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      $this->balanceEnergy += $counter * $building->getBalanceEnergy();
    }
  }
  
  
  /**
   * Initializes the iron balance of the base.
   * 
   * This step takes into account the production of the mines (and the iron
   * upkeep of other buildings if there are any buildings that require iron
   * upkeep). It also takes into account the celestial body's iron density,
   * which affects the mines' productivity.
   * 
   * If the energy balance is negative when calling this method and there is
   * no more stored energy on the base, the production of the mines drops to
   * 50%.
   */
  private function initIronBalance()
  {
    $this->balanceIron = 0.0;
    $ironDensity = $this->base->celestialBody->density_iron;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      $mod = 1.0;
      
      // Positive balance means, the building is a production building.
      // These are influenced by the resource density of the planet and by the
      // production drop in case of energy shortage.
      if ($building->getBalanceIron() > 0.0)
      {
        $mod = $ironDensity;
        
        if ($this->balanceEnergy < 0 && intval($this->base->stored_energy) === 0) {
          $mod *= 0.5;
        }
      }
        
      $this->balanceIron += $counter * $building->getBalanceIron() * $mod;
    }
  }
  
  
  /**
   * Initializes the chemicals balance of the base.
   * 
   * This step takes into account the production of the mines (and the chemicals
   * upkeep of other buildings if there are any buildings that require chemicals
   * upkeep). It also takes into account the celestial body's chemicals density,
   * which affects the mines' productivity.
   * 
   * If the energy balance is negative when calling this method and there is
   * no more stored energy on the base, the production of the mines drops to
   * 50%.
   */
  private function initChemicalsBalance()
  {
    $this->balanceChemicals = 0.0;
    $chemicalsDensity = $this->base->celestialBody->density_chemicals;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      $mod = 1.0;
      
      // Positive balance means, the building is a production building.
      // These are influenced by the resource density of the planet and by the
      // production drop in case of energy shortage.
      if ($building->getBalanceChemicals() > 0.0)
      {
        $mod = $chemicalsDensity;
        
        if ($this->balanceEnergy < 0 && intval($this->base->stored_energy) === 0) {
          $mod *= 0.5;
        }
      }
        
      $this->balanceChemicals += $counter * $building->getBalanceChemicals() * $mod;
    }
  }
  
  
  /**
   * Initializes the ice balance of the base.
   * 
   * This step takes into account the production of the mines (and the ice
   * upkeep of other buildings if there are any buildings that require ice
   * upkeep). It also takes into account the celestial body's ice density,
   * which affects the mines' productivity.
   * 
   * If the energy balance is negative when calling this method and there is
   * no more stored energy on the base, the production of the mines drops to
   * 50%.
   * 
   * Remember, this will only give you a snap-shot of the ice production. Since
   * the ice production depends on the ice density and the ice density
   * decreases depending on how much ice is produced, we need to interpolate
   * the real production in a given amount of time.
   */
  private function initIceBalance()
  {
    $this->balanceIce = 0.0;
    $iceDensity = $this->base->celestialBody->density_ice;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      $mod = 1.0;
      
      // Positive balance means, the building is a production building.
      // These are influenced by the resource density of the planet and by the
      // production drop in case of energy shortage.
      if ($building->getBalanceIce() > 0.0)
      {
        $mod = $iceDensity;
        
        if ($this->balanceEnergy < 0 && intval($this->base->stored_energy) === 0) {
          $mod *= 0.5;
        }
      }
        
      $this->balanceIce += $counter * $building->getBalanceIce() * $mod;
    }
  }
  
  
  /**
   * Initializes the water balance of the base.
   * 
   * This step takes into account:
   * 
   * 1) Planetary specialities.
   * 2) Possible production of water according to buildings on the base.
   * 3) Configured production amount.
   * 4) Energy balance of the base.
   * 5) upkeep of buildings
   * 6) how many people live on the planet (50 people require 1 water, or 1
   *    person requires 0.02 water)
   */
  private function initWaterBalance()
  {
    $this->balanceWater = 0.0;
    
    // 1) Planetary specialities.
    // TODO implement
    
    // 2) Production of water. However, we can only produce as much water as
    //    long as there is energy on the base (energy balance will be modified
    //    accordingly).
    $producibleWater = 0.0;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      
      // Positive balance means, the building is a production building.
      if ($building->getBalanceWater() > 0.0) {
        $producibleWater += $counter * $building->getBalanceWater();
      }
    }
    
    // 3) Configured production amount.
    // null means "produce as much as possible"
    // If there is something configured, just make sure it's not more than what
    // is possible according to the production buildings.
    if ($this->base->produced_water !== null)
    {
      $waterToProduce = intval($this->base->produced_water);
      $producibleWater = min( $producibleWater, $waterToProduce );
    }

    // 4) Energy balance of the base
    if ($this->balanceEnergy < $producibleWater && intval($this->base->stored_energy) === 0) {
      $producibleWater = max( 0, $this->balanceEnergy );
    }
    
    $this->balanceEnergy -= $producibleWater;
    $this->balanceIce -= 2 * $producibleWater;
    $this->balanceWater += $producibleWater;
    
    // 3) upkeep of buildings
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      
      // Negative balance means, the building requires water for its upkeep.
      if ($building->getBalanceWater() < 0.0) {
        $this->balanceWater += $counter * $building->getBalanceWater();
      }
    }
    
    // 4) how many people live on the planet (50 people require 1 water, or 1
    //    person requires 0.02 water)
    $this->balanceWater -= 0.02 * $this->base->stored_people;
  }
  
  
  /**
   * Initializes the steel balance of the base.
   * 
   * This step takes into account:
   * 
   * 1) Possible production of steel according to buildings on the base.
   * 2) Configured production amount.
   * 3) Energy balance of the base.
   */
  private function initSteelBalance()
  {
    $this->balanceSteel = 0.0;
    
    // 1) Possible production of steel according to buildings on the base.
    $producibleSteel = 0.0;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      $producibleSteel += $counter * $building->getBalanceSteel();
    }
    
    // 2) Configured production amount.
    // null means "produce as much as possible"
    // If there is something configured, just make sure it's not more than what
    // is possible according to the production buildings.
    if ($this->base->produced_steel !== null)
    {
      $steelToProduce = intval($this->base->produced_steel);
      $producibleSteel = min( $producibleSteel, $steelToProduce );
    }
    
    // 4) Energy balance of the base
    if ($this->balanceEnergy < $producibleSteel && intval($this->base->stored_energy) === 0) {
      $producibleSteel = max( 0, $this->balanceEnergy );
    }
    
    $this->balanceEnergy -= $producibleSteel;
    $this->balanceIron -= 2 * $producibleSteel;
    $this->balanceSteel += $producibleSteel;
  }
  
  
  /**
   * Initializes the vv4a balance of the base.
   * 
   * This step takes into account:
   * 
   * 1) Possible production of vv4a according to buildings on the base.
   * 2) Configured production amount.
   * 3) Energy balance of the base.
   */
  private function initVv4aBalance()
  {
    $this->balanceVv4a = 0.0;
    
    // 1) Possible production of vv4a according to buildings on the base.
    $producibleVv4a = 0.0;
    
    foreach ($this->base->getBuildingCounters() as $id => $counter)
    {
      $building = $this->buildingFinder->getById( $id );
      $producibleVv4a += $counter * $building->getBalanceVv4a();
    }
    
    // 2) Configured production amount.
    // null means "produce as much as possible"
    // If there is something configured, just make sure it's not more than what
    // is possible according to the production buildings.
    if ($this->base->produced_vv4a !== null)
    {
      $vv4aToProduce = intval($this->base->produced_vv4a);
      $producibleVv4a = min( $producibleVv4a, $vv4aToProduce );
    }
    
    // 4) Energy balance of the base
    if ($this->balanceEnergy < $producibleVv4a && intval($this->base->stored_energy) === 0) {
      $producibleVv4a = max( 0, $this->balanceEnergy );
    }
    
    $this->balanceEnergy -= $producibleVv4a;
    $this->balanceSteel -= 2 * $producibleVv4a;
    $this->balanceVv4a += $producibleVv4a;
  }
}
