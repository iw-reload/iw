<?php

namespace common\entities\celestialBodySpecialties;

use common\entities\CelestialBodySpecialty as CelestialBodySpecialtyEntity;

/**
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class InstableCore extends CelestialBodySpecialtyEntity
{
  public function getType() {
    return CelestialBodySpecialtyEntity::DISCR_INSTABLE_CORE;
  }
}
