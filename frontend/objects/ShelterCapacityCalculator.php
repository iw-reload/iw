<?php

namespace frontend\objects;

use frontend\interfaces\BuildingFinderInterface;
use common\models\Base;
use yii\base\Object;

/**
 * Calculates the capacity of a number of shelters.
 *
 * @author ben
 */
class ShelterCapacityCalculator extends Object
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
  
  public function calculateBaseCapacity( Base $base )
  {
    $buildingCounters = $base->getBuildingCounters();
    $shelters = $this->buildingFinder->getByGroup('shelters');
    $baseCapacity = new Resources();
    
    foreach ($shelters as $id => $shelter)
    {
      $counter = $buildingCounters[$id];
      /* @var $shelter \frontend\behaviors\ShelterCapacityBehavior */
      $capacity = $shelter->calculateShelterCapacity( $counter );
      
      $baseCapacity->chemicals += $capacity->chemicals;
      $baseCapacity->energy += $capacity->energy;
      $baseCapacity->ice += $capacity->ice;
      $baseCapacity->iron += $capacity->iron;
      $baseCapacity->population += $capacity->population;
      $baseCapacity->steel += $capacity->steel;
      $baseCapacity->vv4a += $capacity->vv4a;
      $baseCapacity->water += $capacity->water;
    }
    
    return $baseCapacity;
  }
}
