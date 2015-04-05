<?php

namespace common\models;

use common\entities\SpatialDistortion as SpatialDistortionEntity;

/**
 * 
 */
class SpatialDistortion extends CelestialBody
{
  public function __construct( SpatialDistortionEntity $entity ) {
    parent::__construct( $entity );
  }
}
