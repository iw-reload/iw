<?php
namespace frontend\models;

use common\behaviors\CreateExternalIdentityBehavior;
use common\models\User;
use yii\base\Model;

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
      ['username', 'unique', 'targetClass' => '\common\models\User', 'targetAttribute' => 'name', 'message' => 'This username has already been taken.'],
      ['username', 'string', 'min' => 2, 'max' => 255],
      
      [['authProviderName', 'externalUserId'], 'required'],
      [['authProviderName', 'externalUserId'], 'string', 'max' => 32],
      
    ];
  }
  
  public function getAuthProviderName() {
    return $this->_authProviderName;
  }

  public function setAuthProviderName($authProviderName) {
    $this->_authProviderName = strval($authProviderName);
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
      ? strval($this->_externalUserAttributes['id'])
      : '';
  }
  
  public function isAuthenticated()
  {
    return !($this->getAuthProviderName() === '' || $this->getExternalUserId() === '');
  }
  
  /**
   * Signs user up.
   *
   * @return User|null the saved model or null if saving fails
   */
  public function signup()
  {
    if ($this->validate())
    {
      $createExternalIdentityBehavior = new CreateExternalIdentityBehavior();
      $createExternalIdentityBehavior->authProviderName = $this->getAuthProviderName();
      $createExternalIdentityBehavior->externalUserId = $this->getExternalUserId();

      $user = new User();
      $user->attachBehavior( 0, $createExternalIdentityBehavior );
      $user->name = $this->username;

      $result = $user->save() ? $user : null;
    }
    else
    {
      $result = null;
    }
   
    return $result;
  }
}
