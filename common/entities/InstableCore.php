<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class InstableCore extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_INSTABLE_CORE;
  }
}
