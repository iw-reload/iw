<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\GasGiant as GasGiantEntity;

/**
 * 
 */
class GasGiant extends \common\models\CelestialBody
{
  public function __construct( GasGiantEntity $entity ) {
    parent::__construct( $entity );
  }
}
