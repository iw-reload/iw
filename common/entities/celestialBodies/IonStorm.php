<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class IonStorm extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_ION_STORM;
  }
}
