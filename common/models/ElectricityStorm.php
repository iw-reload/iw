<?php

namespace common\models;

use common\entities\ElectricityStorm as ElectricityStormEntity;

/**
 * 
 */
class ElectricityStorm extends CelestialBody
{
  public function __construct( ElectricityStormEntity $entity ) {
    parent::__construct( $entity );
  }
}
