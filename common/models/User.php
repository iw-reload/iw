<?php

namespace common\models;

use common\behaviors\CreateBaseBehavior;
//use common\behaviors\TaskBehavior;
use frontend\components\task\tasks\ConstructBuildingTask;
use frontend\interfaces\ConstructionTaskProvider;
use frontend\interfaces\TaskProviderInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * 
 * @property Base[] $bases
 * @property Base $currentBase
 * @property Base $mainBase
 * 
 * @property Task[] $tasks
 * @property Task[] $finishedTasks
 * 
 * @property Identity[] $identities
 */
class User  extends ActiveRecord
            implements IdentityInterface, TaskProviderInterface, ConstructionTaskProvider
{
  /**
   * @var \common\models\Base
   */
  private $currentBase = null;
  
  public function init() {
    parent::init();
    
  }
  
  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return '{{%user}}';
  }

  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      TimestampBehavior::className(),
      CreateBaseBehavior::className(),
      // TaskBehavior::className(),
    ];
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['name'], 'required'],
      [['name'], 'string', 'max' => 255],
      [['name'], 'unique']
    ];
  }

  public function transactions()
  {
    return [
      // Make insert transactional, because of CreateBaseBehavior.
      // This ensures every inserted user will get a base. If something happens
      // during base creation, the user insertion will be rolled back.
      'default' => self::OP_INSERT,
    ];
  }  
  
   /**
    * @inheritdoc
    */
   public function attributeLabels()
   {
      return [
        'id' => 'ID',
        'name' => 'Name',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
      ];
   }
   
  /**
   * @inheritdoc
   */
  public static function findIdentity($id)
  {
    return static::findOne(['id' => $id]);
  }

  /**
   * @inheritdoc
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
    throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }

  /**
   * @inheritdoc
   */
  public function getId()
  {
    return $this->getPrimaryKey();
  }

  /**
   * @inheritdoc
   */
  public function getAuthKey()
  {
    throw new NotSupportedException('"getAuthKey" is not implemented.');
  }

  /**
   * @inheritdoc
   */
  public function validateAuthKey($authKey)
  {
    throw new NotSupportedException('"validateAuthKey" is not implemented.');
  }

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
