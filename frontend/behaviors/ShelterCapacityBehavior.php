<?php

namespace frontend\behaviors;

use frontend\objects\ShelterCapacityRule;
use yii\base\Behavior;

/**
 * Can be attached to Components implementing ShelterInterface, so they can
 * calculate shelter capacities.
 *
 * @author ben
 */
class ShelterCapacityBehavior extends Behavior
{
  public function attach($owner)
  {
    if (!$owner instanceof ShelterInterface) {
      throw new InvalidParamException('$owner must implement ShelterInterface.');
    }
    
    parent::attach($owner);
  }
  
  public function calculateShelterCapacity( $nShelters )
  {
    $rule = new ShelterCapacityRule( $this->owner );
    return $rule->calculateShelterCapacity( $nShelters );
  }
}
