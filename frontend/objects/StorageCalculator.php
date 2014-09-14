<?php

namespace frontend\objects;

use common\models\Base;
use frontend\interfaces\BuildingFinderInterface;
use frontend\objects\Resources;
use yii\base\Object;

/**
 * Calculates Storage Capacity of a Base.
 *
 * @author ben
 */
class StorageCalculator extends Object
{
  /**
   * @var BuildingFinderInterface
   */
  private $buildingFinder;
  

  public function __construct( BuildingFinderInterface $buildingFinder, $config = [] )
  {
    $this->buildingFinder = $buildingFinder;
    parent::__construct($config);
  }


  /**
   * @param \common\models\Base $base
   * @return \frontend\objects\Resources
   */
  public function calculateStorage( Base $base )
  {
    $energy = 0;
    $chemicals = 0;
    $iceAndWater = 0;
    
    $warehouses = $this->buildingFinder->getByGroup('warehouses');
    $buildingCounters = $base->getBuildingCounters();
    
    foreach ($warehouses as $id => $building)
    {
      $counter = $buildingCounters[$id];
      
      // TODO: Refactor. Each group of buildings could attach specific behaviors
      //       Those could extend the building with needed functionality.
      //       Warehouses: getStorageCapacity( $nWarehouses )
      //       Shelters: getShelterCapacity( $nWarehouses )
      for ($i = 1; i <= $counter; ++$i)
      {
        $energy += ($i*($i-2)+2) * $building->getStorageEnergy();
        $chemicals += ($i*($i-2)+2) * $building->getStorageChemicals();
        $iceAndWater += ($i*($i-2)+2) * $building->getStorageIceAndWater();
      }      
    }
    
    return new Resources([
      'iron' => PHP_INT_MAX,
      'steel' => PHP_INT_MAX,
      'chemicals' => $chemicals,
      'vv4a' => PHP_INT_MAX,
      // TODO this is restricted. Calculated by Population Calculator.
      'population' => PHP_INT_MAX,
      'ice' => $iceAndWater,
      'water' => $iceAndWater,
      'energy' => $energy,
      'credits' => PHP_INT_MAX,
    ]);
  }
}
