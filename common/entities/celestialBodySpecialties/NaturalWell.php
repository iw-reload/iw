<?php

namespace common\entities\celestialBodySpecialties;

use common\entities\CelestialBodySpecialty as CelestialBodySpecialtyEntity;

/**
 * @Entity
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class NaturalWell extends CelestialBodySpecialtyEntity
{
  public function getType() {
    return CelestialBodySpecialtyEntity::DISCR_NATURAL_WELL;
  }
}
