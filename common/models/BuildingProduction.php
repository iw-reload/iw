<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "building_production".
 *
 * @property integer $id
 * @property integer $production
 * @property integer $building_id
 * @property integer $resource_id
 *
 * @property Resource $resource
 * @property Building $building
 */
class BuildingProduction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%building_production}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['production', 'building_id', 'resource_id'], 'required'],
            [['production', 'building_id', 'resource_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'production' => 'Production',
            'building_id' => 'Building ID',
            'resource_id' => 'Resource ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResource()
    {
        return $this->hasOne(Resource::className(), ['id' => 'resource_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuilding()
    {
        return $this->hasOne(Building::className(), ['id' => 'building_id']);
    }
}
