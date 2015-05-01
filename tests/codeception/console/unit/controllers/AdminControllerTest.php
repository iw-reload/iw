<?php

namespace tests\codeception\console\unit\controllers;

use Codeception\Specify;
use common\entities\User as UserEntity;
use tests\codeception\console\unit\TestCase;

/**
 * Description of AdminControllerTest
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class AdminControllerTest extends TestCase
{
  use Specify;
  
  public function setUp()
  {
    parent::setUp();
    
    $this->clearDb();
    
    $authMan = \Yii::$app->getAuthManager();
    /* @var $authMan \yii\rbac\ManagerInterface */
    $authMan->removeAllAssignments();
    
    /* @var $configBuilder \Codeception\Specify\ConfigBuilder */
    $configBuilder = $this->specifyConfig();
    $configBuilder->shallowClone();
  }
  
  public function testActionIndex()
  {
    $this->specify('AdminController on empty database', function () {
      ob_start();
      \Yii::$app->runAction('admin/index');
      $output = ob_get_flush();
      
      expect('Output should indicate that there are no admins.', $output)->contains( 'No Admins found.' );
    });

    $user1 = new UserEntity();
    $user1->setName('xyz_user1');

    $this->em->persist( $user1 );

    $user2 = new UserEntity();
    $user2->setName('abc_user2');

    $this->em->persist( $user2 );

    $this->em->flush();      
    
    $this->specify('AdminController on database without admins', function () {
      ob_start();
      \Yii::$app->runAction('admin/index');
      $output = ob_get_flush();
      
      expect('Output should indicate that there are no admins.', $output)->contains( 'No Admins found.' );
    });

    // make $user1 and $user2 an admin
    $authMan = \Yii::$app->getAuthManager();
    /* @var $authMan \yii\rbac\ManagerInterface */

    $role = $authMan->getRole( \common\objects\RbacRole::ADMIN );
    $authMan->assign( $role, $user1->getId() );
    $authMan->assign( $role, $user2->getId() );
    
    $this->specify('AdminController on database with admins', function () use ($user1,$user2) {
      ob_start();
      \Yii::$app->runAction('admin/index');
      $output = ob_get_flush();
      
      expect('Output should indicate that user1 is an admin.', $output)->contains( $user1->getName() );
      expect('Output should indicate that user2 is an admin.', $output)->contains( $user2->getName() );
      expect('Output is sorted.', strpos($output,$user2->getName()))->lessThen(strpos($output,$user1->getName()));
    });
  }
  
  public function testActionCreate()
  {
    $options = [];
    $arguments = ['userName' => 'newAdmin'];
    
    $params = $options;
    foreach ($arguments as $key => $value) {
      $params[] = $value;
    }

    $this->specify('Promote non-existent user', function () use ($params) {
      ob_start();
      \Yii::$app->runAction( 'admin/create', $params );
      $output = ob_get_flush();
      
      expect('Output should indicate that the user does not exist.', $output)->contains( 'not found' );
    });

    $this->specify('Promote user already being an admin', function () use ($params) {
      $admin = new UserEntity();
      $admin->setName('newAdmin');
      $this->em->persist($admin);
      
      $this->em->flush();
      
      $authMan = \Yii::$app->getAuthManager();
      /* @var $authMan \yii\rbac\ManagerInterface */
      
      $adminRole = $authMan->getRole( \common\objects\RbacRole::ADMIN );
      $authMan->assign( $adminRole, $admin->getId() );
      
      ob_start();
      \Yii::$app->runAction( 'admin/create', $params );
      $output = ob_get_flush();
      
      expect('Output should indicate that the user already is an admin.', $output)->contains( 'already is an admin' );
    });

    $this->specify('Promote user', function () use ($params) {
      $authMan = \Yii::$app->getAuthManager();
      /* @var $authMan \yii\rbac\ManagerInterface */
      $authMan->removeAllAssignments();
      
      ob_start();
      \Yii::$app->runAction( 'admin/create', $params );
      $output = ob_get_flush();
      
      expect('Output should be empty.', $output)->isEmpty();
    });
  }  
}
