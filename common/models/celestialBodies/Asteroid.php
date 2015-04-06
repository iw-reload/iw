<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\Asteroid as AsteroidEntity;

/**
 * 
 */
class Asteroid extends \common\models\CelestialBody
{
  public function __construct( AsteroidEntity $entity ) {
    parent::__construct( $entity );
  }
}
