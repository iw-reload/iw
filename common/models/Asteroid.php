<?php

namespace common\models;

use common\entities\Asteroid as AsteroidEntity;

/**
 * 
 */
class Asteroid extends CelestialBody
{
  public function __construct( AsteroidEntity $entity ) {
    parent::__construct( $entity );
  }
}
