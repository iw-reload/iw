<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stored_resources".
 *
 * @property integer $id
 * @property integer $base_id
 * @property string $resource_count
 * @property integer $resource_id
 * @property string $modified
 *
 * @property Base $base
 * @property Resource $resource
 */
class StoredResources extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stored_resources}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_id', 'resource_count', 'resource_id', 'modified'], 'required'],
            [['base_id', 'resource_id'], 'integer'],
            [['modified'], 'safe'],
            [['resource_count'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'base_id' => 'Base ID',
            'resource_count' => 'Resource Count',
            'resource_id' => 'Resource ID',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(Base::className(), ['id' => 'base_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['id' => 'resource_id']);
    }
}
