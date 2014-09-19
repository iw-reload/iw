<?php
namespace frontend\models;

use common\models\User;
use common\models\Identity;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
  private $_authProviderName = '';
  private $_authProviderTitle = '';
  private $_externalUserAttributes = [];

  public $username;

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      ['username', 'filter', 'filter' => 'trim'],
      ['username', 'required'],
      ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
      ['username', 'string', 'min' => 2, 'max' => 255],
      
      [['authProviderName', 'externalUserId'], 'required'],
      ['externalUserId', 'filter', 'filter' => 'strval'],
      [['authProviderName', 'externalUserId'], 'string', 'max' => 32],
      
    ];
  }
  
  public function getAuthProviderName() {
    return $this->_authProviderName;
  }

  public function setAuthProviderName($authProviderName) {
    $this->_authProviderName = $authProviderName;
  }
    
  public function getAuthProviderTitle() {
    return $this->_authProviderTitle;
  }
  
  public function setAuthProviderTitle($authProviderTitle) {
    $this->_authProviderTitle = $authProviderTitle;
  }
  
  public function getExternalUserAttributes() {
    return $this->_externalUserAttributes;
  }

  public function setExternalUserAttributes($externalUserAttributes) {
    $this->_externalUserAttributes = $externalUserAttributes;
  }
    
  public function getExternalUserName()
  {
    return is_array($this->_externalUserAttributes) && array_key_exists('login', $this->_externalUserAttributes)
      ? $this->_externalUserAttributes['login']
      : 'Anonymous';
  }
  
  public function getExternalUserId()
  {
    return is_array($this->_externalUserAttributes) && array_key_exists('id', $this->_externalUserAttributes)
      ? $this->_externalUserAttributes['id']
      : '';
  }
  
  public function setExternalUserId( $externalUserId ) {
    $this->_externalUserAttributes['id'] = $externalUserId;
  }
  
  public function isAuthenticated()
  {
    return !(empty($this->getAuthProviderName()) || empty($this->getExternalUserId()));
  }
  
  /**
   * Signs user up.
   *
   * @return User|null the saved model or null if saving fails
   */
  public function signup()
  {
    $user = null;
    
    if ($this->validate())
    {
      $user = new User();
      $user->username = $this->username;
      
      $transaction = $user->getDb()->beginTransaction();
      
      try
      {
        if (!$user->save()) {
          throw new Exception( 'Failed to save user.' );
        }
        
        $identity = new Identity();
        $identity->auth_provider = $this->getAuthProviderName();
        $identity->external_user_id = $this->getExternalUserId();
        $identity->internal_user_id = $user->id;

        if (!$identity->save()) {
          throw new Exception( 'Failed to save identity.' );
        }

        $transaction->commit();
      }
      catch(Exception $e)
      {
        $transaction->rollback();
        $user = null;
      }      
    }
    
    return $user;
  }
}
