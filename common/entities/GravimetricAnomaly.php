<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class GravimetricAnomaly extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_GRAVIMETRIC_ANOMALY;
  }
}
