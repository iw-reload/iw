<?php

namespace common\entityRepositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @author ben
 */
class CelestialBody extends EntityRepository
{
  /**
   * Returns a CelestialBody not being owned by any user (no Outpost on it),
   * which is not in a chaos galaxy and which is not in a system causing any
   * effects.
   * @return \common\entities\CelestialBody
   * @throws \Doctrine\ORM\NoResultException
   */
  public function findForNewPlayer()
  {
    $qb = $this->_em->createQueryBuilder();
    
    // first, find systems not in a chaos galaxy without modifier
    $tempResult = $qb
      ->select('s.id AS system_id', 'm.id AS modifier_id')
      ->from(\common\entities\System::class, 's')
      ->join('s.galaxy', 'g', Join::WITH, $qb->expr()->neq('mod(g.number,4)', 0))
      ->leftJoin('s.modifier', 'm')
      ->where($qb->expr()->isNull('m.id'))
      ->getQuery()
      ->getArrayResult();
    
    if (empty($tempResult)) {
      throw new \Doctrine\ORM\NoResultException();
    }
    
    $systemIds = [];
    array_walk( $tempResult, function( $row ) use (&$systemIds) {
      $systemIds[] = $row['system_id'];
    });
    
    // now, find celestial bodies in these systems, that don't have outposts
    $qb->resetDQLParts();
    $result = $qb
      ->select('c', 'o.id', 's.id')
      ->from(\common\entities\CelestialBody::class, 'c')
      ->join('c.system', 's')
      ->leftJoin('c.outpost', 'o')
      ->where($qb->expr()->andX(
        $qb->expr()->isNull('o.id'),
        $qb->expr()->in('s.id', $systemIds)
      ))
      ->setMaxResults(1)
      ->getQuery()
      ->getSingleResult();
    
    return empty($result) ? null : array_shift($result);
  }
}
