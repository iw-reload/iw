<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class SpatialDistortion extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_SPATIAL_DISTORTION;
  }
}
