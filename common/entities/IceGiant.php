<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class IceGiant extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_ICE_GIANT;
  }
}
