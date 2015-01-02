<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class ElectricityStorm extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_ELECTRICITY_STORM;
  }
}
