<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class OldRuins extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_OLD_RUINS;
  }
}
