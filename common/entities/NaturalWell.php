<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class NaturalWell extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_NATURAL_WELL;
  }
}
