<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\interfaces\ConstructionTaskProvider;

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
            implements IdentityInterface, ConstructionTaskProvider
{
  /**
   * @var \common\models\Base
   */
  private $currentBase = null;
  
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
    return $this->hasMany( Base::className(), ['user_id' => 'id'] );
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
    return $this->hasMany(Identity::className(), ['internal_user_id' => 'id']);
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
   * @return \yii\db\ActiveQuery
   */
  public function getTasks()
  {
    return $this->hasMany(Task::className(), ['user_id' => 'id']);
  }

  /** 
   * @return \yii\db\ActiveQuery 
   */ 
  public function getFinishedTasks() 
  { 
    return $this->hasMany( Task::className(), ['user_id' => 'id'] )->finished(); 
  }
  
  /**
   * @return Task[]
   */
  public function getConstructionTasks()
  {
    $result = [];
    
    foreach ($this->tasks as $task)
    {
      if ($task->type === Task::TYPE_BUILDING_CONSTRUCTION) {
        $result[] = $task;
      }
    }
    
    return $result;
  }
  
}
