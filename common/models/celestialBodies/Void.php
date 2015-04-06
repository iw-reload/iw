<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\Void as VoidEntity;

/**
 * 
 */
class Void extends \common\models\CelestialBody
{
  public function __construct( VoidEntity $entity ) {
    parent::__construct( $entity );
  }
}
