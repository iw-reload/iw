<?php

namespace common\behaviors;

use common\models\Identity;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Cares for creating an external user identity for newly created users.
 * 
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class CreateExternalIdentityBehavior extends Behavior
{
  public $authProviderName = '';
  public $externalUserId = '';
  
  public function events()
  {
    return [
      ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
    ];
  }
 
  public function afterInsert()
  {
    $user = $this->getUser();
    
    $identity = new Identity();
    $identity->auth_provider = $this->authProviderName;
    $identity->external_user_id = $this->externalUserId;
    $identity->internal_user_id = $user->id;

    if (!$identity->save()) {
      throw new Exception( 'Failed to save identity. Errors: ' . print_r($identity->errors,true) );
    }
  }
  
  /**
   * @return \common\models\User
   */
  private function getUser()
  {
    return $this->owner;
  }
}
