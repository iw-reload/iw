<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Natives extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_NATIVES;
  }
}
