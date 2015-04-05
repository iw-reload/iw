<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Radioative extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_RADIOACTIVE;
  }
}
