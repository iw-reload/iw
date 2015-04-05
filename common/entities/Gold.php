<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Gold extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_GOLD;
  }
}
