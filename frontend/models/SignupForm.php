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
    $user = null;
    
    if ($this->validate())
    {
      $transaction = \Yii::$app->db->beginTransaction();
      
      try
      {
        // TODO: Creating a user isn't that easy.
        //       First, we need to create the start planet and stuff for the
        //       player.
        //       Also, currently the base must belong to a player and a player
        //       must have a main base. Break this circular dependency. We must
        //       start creating something.
        
        // find one without a base
        // set default attributes
        $celestialBody = new \common\models\CelestialBody();
        
        if (!$celestialBody->save()) {
          throw new \Exception( 'Failed to save celestial body.' );
        }
                  
        $base = new \common\models\Base();
        $base->id = $celestialBody->id;

        if (!$base->save()) {
          throw new \Exception( 'Failed to save base.' );
        }
        
        // place some default buildings on the base
        
        $user = new User();
        $user->name = $this->username;
        $user->main_base_id = $base->id;
        
        if (!$user->save()) {
          throw new \Exception( 'Failed to save user.' );
        }
        
        $identity = new Identity();
        $identity->auth_provider = $this->getAuthProviderName();
        $identity->external_user_id = $this->getExternalUserId();
        $identity->internal_user_id = $user->id;

        if (!$identity->save()) {
          throw new \Exception( 'Failed to save identity.' );
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
