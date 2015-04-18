<?php

namespace common\entityRepositories;

use Doctrine\ORM\EntityRepository;

/**
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class User extends EntityRepository
{
  /**
   * @param string $name
   * @return \common\entities\User
   */
  public function findOneByName( $name )
  {
    return $this->findOneBy([
      'name' => $name,
    ]);
  }
  
  /**
   * @param string $term
   * @return \common\entities\User[]
   */
  public function findByNameLike( $term )
  {
    $qb = $this->createQueryBuilder('u');
    
    $result = $qb
      ->where($qb->expr()->like('u.name',':term'))
      ->orderBy('u.name')
      ->setParameter('term', $term)
      ->getQuery()
      ->getResult();
    
    return $result;
  }
  
  /**
   * Finds User instances fullfilling a certain criteria.
   * 
   * The method passes all User-Ids to the provided callback. If the callback
   * returns true, the User will be loaded and included in the result.
   * 
   * @param callable $filterByIdCallback
   * @return \common\entities\User[]
   */
  public function findAllByIdFilter( $filterByIdCallback )
  {
    $qb = $this->createQueryBuilder('u');
    
    // first, find systems not in a chaos galaxy without modifier
    $userIds = $qb
      ->select('u.id')
      ->getQuery()
      ->getResult();
    
    if (empty($userIds))
    {
      $result = [];
    }
    else
    {
      $flatUserIds = [];
      array_walk( $userIds, function( $row ) use (&$flatUserIds) {
        $flatUserIds[] = $row['id'];
      });
      
      $filteredUserIds = array_filter( $flatUserIds, $filterByIdCallback );
      $result = empty($filteredUserIds) ? [] : $this->findBy(['id' => $filteredUserIds]);
    }
    
    return $result;
  }
}
