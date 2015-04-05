<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class AsteroidBelt extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_ASTEROID_BELT;
  }
}
