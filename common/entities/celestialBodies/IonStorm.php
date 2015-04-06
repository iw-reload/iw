<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author ben
 */
class IonStorm extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_ION_STORM;
  }
}
