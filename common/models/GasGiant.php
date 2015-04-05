<?php

namespace common\models;

use common\entities\GasGiant as GasGiantEntity;

/**
 * 
 */
class GasGiant extends CelestialBody
{
  public function __construct( GasGiantEntity $entity ) {
    parent::__construct( $entity );
  }
}
