<?php

namespace common\models;

use common\queries\TaskQuery;
use frontend\interfaces\TaskInterface;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $type one of the TYPE_* constants
 * @property array $data can be used to configure a Task
 * @property \DateTime $finished
 * @property integer $user_id
 *
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
  const TYPE_BUILDING_CONSTRUCTION = 'contruct.building';
  
  public function behaviors() {
    return array_merge(parent::behaviors(), [
      [
        'class' => AttributeBehavior::className(),
        'attributes' => [
          ActiveRecord::EVENT_AFTER_FIND => 'data',
        ],
        'value' => function ($event) {
          return unserialize( $this->data );
        },
      ],
      [
        'class' => AttributeBehavior::className(),
        'attributes' => [
          ActiveRecord::EVENT_AFTER_FIND => 'finished',
        ],
        'value' => function ($event) {
          return new \DateTime( $this->finished );
        },
      ],
    ]);
  }
  
  /**
   * @inheritdoc
   * @return TaskQuery
   */
  public static function find()
  {
    return new TaskQuery(get_called_class());
  } 
  
  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return '{{%task}}';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['type', 'data', 'finished', 'user_id'], 'required'],
      ['data', function ( $attribute, $params ) {
        if (!is_array($this->$attribute)) {
          $this->addError( $attribute, 'Data must be a configuration array.' );
        }
      }],
      ['data', 'filter', 'filter' => function ($value) {
        return serialize( $value );
      }],
      ['finished', 'filter', 'filter' => function ($value) {
        return ($value instanceof \DateTime) ? $value->format( \DateTime::RFC3339 ) : $value;
      }],
      ['finished', 'date', 'format' => \DateTime::RFC3339 ],
      [['type'], 'string', 'max' => 255],
      ['type', 'validateType'],
      ['user_id', 'integer'],
    ];
  }

  /**
   * Ensures Task::type is one of the Task::TYPE_* constants.
   * 
   * @param string $attribute
   * @param array $params
   */
  public function validateType( $attribute, $params )
  {
    $rc = new \ReflectionClass( $this );

    $validTypes = [];

    foreach ($rc->getConstants() as $key => $val)
    {
      if (strpos($key,'TYPE_') === 0) {
        $validTypes[] = $val;
      }
    }

    if (array_search($this->$attribute,$validTypes,true) === false) {
      $this->addError( $attribute, 'Type must be one of the TYPE_* constants.' );
    }
  }
    
  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'type' => 'Type',
      'data' => 'Data',
      'finished' => 'Finished',
      'user_id' => 'User ID',
    ];
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
  }
}
