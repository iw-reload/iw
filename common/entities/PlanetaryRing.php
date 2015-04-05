<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class PlanetaryRing extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_PLANETARY_RING;
  }
}
