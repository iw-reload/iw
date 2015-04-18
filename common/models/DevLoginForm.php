<?php

namespace common\models;

use common\entityRepositories\User as UserRepository;
use common\entities\User as UserEntity;
use common\models\User as UserModel;
use yii\base\Model;
use Yii;

/**
 * Auth form
 */
class DevLoginForm extends Model
{
  /**
   * @var string
   */
  public $userName;
  /**
   * @var UserRepository
   */
  private $userRepository = null;
  
  public function __construct(UserRepository $userRepository, $config = array())
  {
    parent::__construct($config);
    $this->userRepository = $userRepository;
  }
  
  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['userName'], 'required'],
      [['userName'], \common\validators\doctrine\ExistValidator::class, 'repository' => $this->userRepository, 'targetAttribute' => 'name'],
    ];
  }
  
  /**
   * @return boolean whether the user is logged in successfully
   */
  public function login()
  {
    $user = $this->getUser();
    
    return $user instanceof \yii\web\IdentityInterface
      ? Yii::$app->user->login( $user )
      : false;
  }

  /**
   * @return UserModel|null
   */
  public function getUser()
  {
    if ($this->validate())
    {
      $userEntity = $this->userRepository->findOneByName( $this->userName );
      return new UserModel( $userEntity );
    }

    return null;
  }
  
}
