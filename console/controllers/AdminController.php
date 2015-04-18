<?php

namespace console\controllers;

use common\entities\User as UserEntity;
use common\entityRepositories\User as UserRepository;
use common\components\doctrine\DoctrineComponent;
use common\models\User as UserModel;
use common\objects\RbacRole;
use Doctrine\ORM\EntityManager;
use yii\console\Controller;
use yii\di\Instance;
use yii\rbac\ManagerInterface as AuthManagerInterface;

/**
 * Description of AdminController
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class AdminController extends Controller
{
  /**
   * @var AuthManagerInterface
   */
  public $authManager = 'authManager';
  /**
   * @var DoctrineComponent
   */
  public $doctrine = 'doctrine';

  public function actionIndex()
  {
    $authManager = $this->getAuthManager();
    $userRepository = $this->getUserRepository();
    
    $admins = $userRepository->findAllByIdFilter( function($userId) use ($authManager) {
      return $authManager->checkAccess( $userId, RbacRole::ADMIN );
    });

    if (empty($admins))
    {
      echo "No Admins found.\n";
    }
    else
    {
      usort( $admins, function(UserEntity $lhs, UserEntity $rhs) {
        return strcmp( $lhs->getName(), $rhs->getName() );
      });

      echo "Admins:\n";
      foreach ($admins as $admin) {
        echo " - '{$admin->getName()}'\n";
      }
    }
  }

  public function actionCreate( $userName )
  {
    $authManager = $this->getAuthManager();
    $userRepository = $this->getUserRepository();
    $user = $userRepository->findOneByName( $userName );
    
    if ($user instanceof UserEntity)
    {
      if ($authManager->checkAccess($user->getId(),RbacRole::ADMIN))
      {
        echo "User '{$userName}' already is an admin!\n";
      }
      else
      {
        $role = $authManager->getRole( RbacRole::ADMIN );
        $authManager->assign( $role, $user->getId() );
      }
    }
    else
    {
      echo "User '{$userName}' not found!\n";
    }
  }

  /**
   * @return AuthManagerInterface
   */
  private function getAuthManager()
  {
    $this->authManager = Instance::ensure( $this->authManager, AuthManagerInterface::class );
    return $this->authManager;
  }
  
  /**
   * @return DoctrineComponent
   */
  private function getDoctrineComponent()
  {
    $this->doctrine = Instance::ensure( $this->doctrine, DoctrineComponent::class );
    return $this->doctrine;
  }
  
  /**
   * @return EntityManager
   */
  private function getEntityManager()
  {
    $doctrine = $this->getDoctrineComponent();
    return $doctrine->getEntityManager();
  }
  
  /**
   * @return UserRepository
   */
  private function getUserRepository()
  {
    $em = $this->getEntityManager();
    return $em->getRepository( UserEntity::class );
  }
}
