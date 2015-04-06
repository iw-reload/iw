<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\GravimetricAnomaly as GravimetricAnomalyEntity;

/**
 * 
 */
class GravimetricAnomaly extends \common\models\CelestialBody
{
  public function __construct( GravimetricAnomalyEntity $entity ) {
    parent::__construct( $entity );
  }
}
