<?php

namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Auth form
 */
class DevLoginForm extends Model
{
  public $userName;
  
  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['userName'], 'required'],
      [['userName'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'name'],
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
   * @return User|null
   */
  public function getUser()
  {
    $result = null;
    
    if ($this->validate())
    {
      $result = User::find()
        ->where(['name' => $this->userName])
        ->one();
    }

    return $result;
  }
  
}
