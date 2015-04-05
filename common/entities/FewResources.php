<?php

namespace common\entities;

/**
 * @Entity
 * @author ben
 */
class FewResources extends CelestialBodySpecialty
{
  public function getType() {
    return CelestialBodySpecialty::DISCR_FEW_RESOURCES;
  }
}
