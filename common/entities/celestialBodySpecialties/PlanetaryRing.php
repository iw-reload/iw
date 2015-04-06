<?php

namespace common\entities\celestialBodySpecialties;

use common\entities\CelestialBodySpecialty as CelestialBodySpecialtyEntity;

/**
 * @Entity
 * @author ben
 */
class PlanetaryRing extends CelestialBodySpecialtyEntity
{
  public function getType() {
    return CelestialBodySpecialtyEntity::DISCR_PLANETARY_RING;
  }
}
