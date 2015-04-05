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
   * @throws \Doctrine\ORM\NoResultException
   */
  public function getFreeCelestialBody()
  {
    $result = $this->_em->createQueryBuilder()
      ->select('c', 'o.id')
      ->from('common\entities\CelestialBody', 'c')
      ->leftJoin('c.outpost', 'o')
      ->where('o.id IS NULL')
      ->setMaxResults(1)
      ->getQuery()
      ->getSingleResult();
    
    return empty($result) ? null : array_shift($result);
  }  
}
