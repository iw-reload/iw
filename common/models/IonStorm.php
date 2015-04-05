<?php

namespace common\models;

use common\entities\IonStorm as IonStormEntity;

/**
 * 
 */
class IonStorm extends CelestialBody
{
  public function __construct( IonStormEntity $entity ) {
    parent::__construct( $entity );
  }
}
