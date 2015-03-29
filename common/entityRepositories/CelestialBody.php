<?php

namespace common\entityRepositories;

use Doctrine\ORM\EntityRepository;

/**
 * @author ben
 */
class CelestialBody extends EntityRepository
{
  /**
   * Returns a CelestialBody not being owned by any user (no Outpost on it).
   * @return \common\entities\CelestialBody
   */
  public function getFreeCelestialBody()
  {
    return $this->_em
      ->createQuery('SELECT c, o.id FROM common\entities\CelestialBody c LEFT JOIN c.outpost o WHERE o.id IS NULL')
      ->getOneOrNullResult();
  }  
}
