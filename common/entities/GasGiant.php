<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class GasGiant extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_GAS_GIANT;
  }
}
