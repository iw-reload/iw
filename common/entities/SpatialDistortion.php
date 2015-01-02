<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class SpatialDistortion extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_SPATIAL_DISTORTION;
  }
}
