<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Identity;
use \common\models\User;

/**
 * Auth form
 */
class AuthForm extends Model
{
  public $authProvider;
  public $externalUserId;

  private $_user = false;
  
  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['authProvider', 'externalUserId'], 'required'],
      ['externalUserId', 'filter', 'strval'],
      [['authProvider', 'externalUserId'], 'string', 'max' => 32],
    ];
  }

  public function isRegistered()
  {
    return $this->getUser() instanceof User;
  }
  
  /**
   * @return boolean whether the user is logged in successfully
   */
  public function login()
  {
    return Yii::$app->user->login( $this->getUser() );
  }

  /**
   * @return User|null
   */
  public function getUser()
  {
    if ($this->_user === false)
    {
      $externalIdentity = Identity::findOne([
        'auth_provider' => $this->authProvider,
        'external_user_id' => $this->externalUserId,
      ]);
      
      $this->_user = $externalIdentity instanceof Identity
        ? $externalIdentity->internalUser
        : null;
    }

    return $this->_user;
  }
  
}
