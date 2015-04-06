<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\IonStorm as IonStormEntity;

/**
 * 
 */
class IonStorm extends \common\models\CelestialBody
{
  public function __construct( IonStormEntity $entity ) {
    parent::__construct( $entity );
  }
}
