<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Asteroid extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_ASTEROID;
  }
}
