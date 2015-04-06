<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\SpatialDistortion as SpatialDistortionEntity;

/**
 * 
 */
class SpatialDistortion extends \common\models\CelestialBody
{
  public function __construct( SpatialDistortionEntity $entity ) {
    parent::__construct( $entity );
  }
}
