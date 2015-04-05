<?php

namespace common\models;

use common\entities\Void as VoidEntity;

/**
 * 
 */
class Void extends CelestialBody
{
  public function __construct( VoidEntity $entity ) {
    parent::__construct( $entity );
  }
}
