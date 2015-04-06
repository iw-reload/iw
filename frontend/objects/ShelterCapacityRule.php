<?php

namespace frontend\objects;

use frontend\interfaces\ShelterInterface;
use yii\base\Object;

/**
 * Implements the rule for shelter capacity growth.
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class ShelterCapacityRule extends Object
{
  /**
   * @var ShelterInterface
   */
  private $shelter;
    
  public function __construct( ShelterInterface $shelter, $config = [] )
  {
    $this->shelter = $shelter;
    parent::__construct($config);
  }
  
  /**
   * @param int $nShelters
   * @return \frontend\objects\Resources
   */
  public function calculateShelterCapacity( $nShelters )
  {
    $capacity = $this->shelter->getShelterCapacity();
    
    // Every population shelter provides a fixed amount of shelter capacity.
    $capacity->population *= $nShelters;
    
    // All other shelters provide a growing amount of shelter capacity.
    // The first shelter provides capacity n, the second provides additional
    // capacity 2*n, the third provides additional capacity 3*n, ...
    // 
    // x = capacity of first shelter
    // capacity(n) = 1*x + 2*x + ... + n*x
    // capacity(n) = x * (1 + 2 + ... + n)
    // Gaussian sum
    // capacity(n) = x * ((n^2 + n) / 2)
    $multiplier = (\pow($nShelters,2) + $nShelters) / 2.0;
    
    $capacity->chemicals *= $multiplier;
    $capacity->credits *= $multiplier;
    $capacity->energy *= $multiplier;
    $capacity->ice *= $multiplier;
    $capacity->iron *= $multiplier;
    $capacity->steel *= $multiplier;
    $capacity->vv4a *= $multiplier;
    $capacity->water *= $multiplier;
    
    return $capacity;
  }  
}
