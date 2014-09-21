<?php
namespace frontend\models;

use common\components\universe\UniverseComponent;
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
    if (!$this->validate()) {
      return null;
    }
    
    $transaction = \Yii::$app->db->beginTransaction();

    try
    {
      // Creating a user:
      // - Create the user
      // - Create identity and link it to user
      // - Find a celestial body for the player and reset its attributes
      //   to default values.
      // - Create a base on that celestial body
      // - Initialize the base with basic buildings
      // 

      $user = new User();
      $user->name = $this->username;

      if (!$user->save()) {
        throw new \Exception( 'Failed to save user. Errors: ' . print_r($user->errors,true) );
      }

      $identity = new Identity();
      $identity->auth_provider = $this->getAuthProviderName();
      $identity->external_user_id = $this->getExternalUserId();
      $identity->internal_user_id = $user->id;

      if (!$identity->save()) {
        throw new \Exception( 'Failed to save identity. Errors: ' . print_r($identity->errors,true) );
      }
      
      /* @var $universe UniverseComponent */
      $universe = \Yii::$app->universe;

      // 1) Find a celestial body for the player and reset its attributes
      //    to default values.
      $celestialBody = $universe->resetCelestialBody(
        $universe->chooseCelestialBodyForNewPlayer()
      );

      if (!$celestialBody->save()) {
        throw new \Exception( 'Failed to save celestial body. Errors: ' . print_r($celestialBody->errors,true) );
      }

      $base = new \common\models\Base();
      $base->id = $celestialBody->id;
      $base->user_id = $user->id;
      $base->name = \Yii::t( 'app', "{username}'s colony", [
        'username' => $user->name,
      ]);
      // TODO make configurable
      $base->stored_iron        = 5000;
      $base->stored_steel       = 2000;
      $base->stored_chemicals   = 5000;
      $base->stored_vv4a        = 0;
      $base->stored_ice         = 5000;
      $base->stored_water       = 5000;
      $base->stored_energy      = 5000;
      $base->stored_people      = 500;
      $base->stored_credits     = 5000;
      //$base->stored_last_update = ;
            
      if (!$base->save()) {
        throw new \Exception( 'Failed to save base. Errors: ' . print_r($base->errors,true) );
      }

      // place some default buildings on the base

      $transaction->commit();
    }
    catch (\Exception $e)
    {
      $transaction->rollback();
      \Yii::error( $e->getMessage() );
      $user = null;
    }
    
    return $user;
  }
}
