<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\User;
use yii\di\Instance;
use common\objects\RbacRole;

/**
 * Description of AdminController
 *
 * @author ben
 */
class AdminController extends Controller
{
  /**
   * @var yii\rbac\ManagerInterface
   */
  public $authManager = 'authManager';

  public function actionIndex()
  {
    $this->authManager = Instance::ensure( $this->authManager, 'yii\rbac\ManagerInterface' );
    
    $userIds = User::find()
      ->select('id')
      ->column();
    
    $adminIds = [];
    foreach ($userIds as $userId)
    {
      if ($this->authManager->checkAccess($userId,RbacRole::ADMIN)) {
        $adminIds[] = $userId;
      }
    }
    
    $admins = User::find()
      ->where(['id' => $adminIds])
      ->indexBy('name')
      ->all();

    if (empty($admins))
    {
      echo "No Admins found.\n";
    }
    else
    {
      ksort( $admins );

      echo "Admins:\n";
      foreach ($admins as $admin) {
        echo " - '{$admin->name}'\n";
      }
    }
  }

  public function actionCreate( $userName )
  {
    $this->authManager = Instance::ensure( $this->authManager, 'yii\rbac\ManagerInterface' );

    $user = User::find()
      ->where(['name' => $userName])
      ->one();
    
    if ($user instanceof User)
    {
      if ($this->authManager->checkAccess($user->id,RbacRole::ADMIN))
      {
        echo "User '{$userName}' already is an admin!\n";
      }
      else
      {
        $role = $this->authManager->getRole( RbacRole::ADMIN );
        $this->authManager->assign( $role, $user->id );
      }
    }
    else
    {
      echo "User '{$userName}' not found!\n";
    }
  }

}
