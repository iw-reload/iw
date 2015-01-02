<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class TerrestrialPlanet extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_TERRESTRIAL_PLANET;
  }
}
