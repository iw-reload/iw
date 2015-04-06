<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author ben
 */
class ElectricityStorm extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_ELECTRICITY_STORM;
  }
}
