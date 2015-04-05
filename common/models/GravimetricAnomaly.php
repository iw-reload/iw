<?php

namespace common\models;

use common\entities\GravimetricAnomaly as GravimetricAnomalyEntity;

/**
 * 
 */
class GravimetricAnomaly extends CelestialBody
{
  public function __construct( GravimetricAnomalyEntity $entity ) {
    parent::__construct( $entity );
  }
}
