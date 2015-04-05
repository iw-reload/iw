<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Moon extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_MOON;
  }
}
