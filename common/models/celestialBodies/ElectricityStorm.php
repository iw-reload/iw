<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\ElectricityStorm as ElectricityStormEntity;

/**
 * 
 */
class ElectricityStorm extends \common\models\CelestialBody
{
  public function __construct( ElectricityStormEntity $entity ) {
    parent::__construct( $entity );
  }
}
