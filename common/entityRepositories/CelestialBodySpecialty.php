<?php

namespace common\entityRepositories;

use common\entities\celestialBodySpecialties\Moon;
use Doctrine\ORM\EntityRepository;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class CelestialBodySpecialty extends EntityRepository
{
  /**
   * @return Moon
   */
  public function getMoon()
  {
    $moon = $this
      ->getEntityManager()
      ->getRepository(Moon::class)
      ->findOneBy(array());
    return $moon;
  }
}
