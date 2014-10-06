<?php

use common\objects\RbacRole;
use yii\db\Migration;

class m141006_103026_initAdminRights extends Migration
{
  private function addChild( $role, $permissionName )
  {
    $auth = \Yii::$app->authManager;

    $permission = $auth->createPermission( $permissionName );
    $auth->add( $permission );
    $auth->addChild( $role, $permission );
  }
  
  public function up()
  {
    $auth = \Yii::$app->authManager;

    $admin = $auth->createRole( RbacRole::ADMIN );
    $auth->add( $admin );
    
    $this->addChild( $admin, RbacRole::BUILDING_CREATE );
    $this->addChild( $admin, RbacRole::BUILDING_DELETE );
    $this->addChild( $admin, RbacRole::BUILDING_LIST );
    $this->addChild( $admin, RbacRole::BUILDING_UPDATE );
    $this->addChild( $admin, RbacRole::BUILDING_VIEW );
    
    $this->addChild( $admin, RbacRole::CELESTIAL_BODY_CREATE );
    $this->addChild( $admin, RbacRole::CELESTIAL_BODY_DELETE );
    $this->addChild( $admin, RbacRole::CELESTIAL_BODY_LIST );
    $this->addChild( $admin, RbacRole::CELESTIAL_BODY_UPDATE );
    $this->addChild( $admin, RbacRole::CELESTIAL_BODY_VIEW );
    
    $this->addChild( $admin, RbacRole::USER_CREATE );
    $this->addChild( $admin, RbacRole::USER_DELETE );
    $this->addChild( $admin, RbacRole::USER_LIST );
    $this->addChild( $admin, RbacRole::USER_UPDATE );
    $this->addChild( $admin, RbacRole::USER_VIEW );
  }
  
  public function down()
  {
      echo "m141006_103026_initAdminRights cannot be reverted.\n";

      return false;
  }
}
