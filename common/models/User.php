<?php

namespace common\models;

use common\entities\User as UserEntity;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class User implements IdentityInterface
{
  /**
   * @var UserEntity
   */
  private $entity = null;
  
  public function __construct(UserEntity $entity) {
    $this->entity = $entity;
  }
  
  // --- IdentityInterface - START --------------------------------------------
  
  /**
   * @inheritdoc
   */
  public static function findIdentity($id) {
    throw new NotSupportedException('"findIdentity" is not implemented.');
  }

  /**
   * @inheritdoc
   */
  public static function findIdentityByAccessToken($token, $type = null) {
    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }

  /**
   * @inheritdoc
   */
  public function getId() {
    return $this->entity->getId();
  }

  /**
   * @inheritdoc
   */
  public function getAuthKey() {
    throw new NotSupportedException('"getAuthKey" is not implemented.');
  }

  /**
   * @inheritdoc
   */
  public function validateAuthKey($authKey) {
    throw new NotSupportedException('"validateAuthKey" is not implemented.');
  }
  
  // --- IdentityInterface - STOP ---------------------------------------------
  
  /**
   * @return \yii\db\ActiveQuery
   */
  public function getBases()
  {
    return $this->hasMany(Base::className(),['user_id' => 'id'])->inverseOf('user');
  }
  
  /**
   * @todo  For now, the main base is simply the first base the user ever built.
   *        We need another algorithm (or class implementing that algorithm)
   *        once we want to give users the possibility to declare another base
   *        as their main base.
   * @return Base
   */
  public function getMainBase()
  {
    $bases = $this->bases;
    
    /* @var $mainBase Base */
    $mainBase = \reset( $bases );
    
    foreach ($bases as $base)
    {
      if ($base->created_at < $mainBase->created_at) {
        $mainBase = $base;
      }
    }
    
    return $mainBase;
  }
  
 /**
   * @return \yii\db\ActiveQuery
   */
  public function getIdentities()
  {
    return $this->hasMany(Identity::className(), ['internal_user_id' => 'id'])->inverseOf('internalUser');
  }
  
  
  /**
   * @return \common\models\Base
   */
  public function getBase( $baseId )
  {
    $result = null;
    
    foreach ($this->bases as $base)
    {
      if ($base->id == $baseId)
      {
        $result = $base;
        break;
      }
    }
    
    return $result;
  }
  
  
  /**
   * @return \common\models\Base The currently selected base.
   */
  public function getCurrentBase()
  {
    if ($this->currentBase === null) {
      $this->currentBase = $this->mainBase;
    }
    
    return $this->currentBase;
  }
  
  public function setCurrentBase( $base )
  {
    if ($base instanceof Base) {
      $this->currentBase = $base;
    } elseif (is_int($base)) {
      $this->currentBase = $this->getBase( $base );
    } else {
      throw new InvalidParamException( 'Pass a Base instance or the ID of a base.' );
    }
  }
  
  /**
   * @return Task[]
   */
  public function getTasks()
  {
    return $this->hasMany( Task::className(), ['user_id' => 'id'] )->all();
  }

  /**
   * @return Task[]
   */
  public function getFinishedTasks() 
  { 
    return $this->hasMany( Task::className(), ['user_id' => 'id'] )->finished()->all(); 
  }
  
  /**
   * @return Task[]
   */
  public function getConstructionTasks()
  {
    $result = [];
    
    foreach ($this->tasks as $task)
    {
      if ($task->type === ConstructBuildingTask::className()) {
        $result[] = $task;
      }
    }
    
    return $result;
  }
  
}
