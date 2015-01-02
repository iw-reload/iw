<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class Void extends CelestialBody
{
  public function getType() {
    return CelestialBody::DISCR_VOID;
  }
}
