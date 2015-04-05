<?php

namespace common\entityRepositories;

use common\entities\Moon;
use Doctrine\ORM\EntityRepository;

/**
 * @author ben
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
