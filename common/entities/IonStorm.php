<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class IonStorm extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_ION_STORM;
  }
}
