<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author ben
 */
class GasGiant extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_GAS_GIANT;
  }
}
