<?php

namespace common\models;

use common\entities\IceGiant as IceGiantEntity;

/**
 * 
 */
class IceGiant extends CelestialBody
{
  public function __construct( IceGiantEntity $entity ) {
    parent::__construct( $entity );
  }
}
