<?php

namespace common\models\celestialBodies;

use common\entities\celestialBodies\IceGiant as IceGiantEntity;

/**
 * 
 */
class IceGiant extends \common\models\CelestialBody
{
  public function __construct( IceGiantEntity $entity ) {
    parent::__construct( $entity );
  }
}
