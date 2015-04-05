<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Toxic extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_TOXIC;
  }
}
