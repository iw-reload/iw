<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author ben
 */
class IceGiant extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_ICE_GIANT;
  }
}
