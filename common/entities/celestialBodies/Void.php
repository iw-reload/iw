<?php

namespace common\entities\celestialBodies;

use common\entities\CelestialBody as CelestialBodyEntity;

/**
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class Void extends CelestialBodyEntity
{
  public function getType() {
    return CelestialBodyEntity::DISCR_VOID;
  }
}
