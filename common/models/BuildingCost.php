<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "building_cost".
 *
 * @property integer $id
 * @property integer $cost
 * @property integer $building_id
 * @property integer $resource_id
 *
 * @property Resource $resource
 * @property Building $building
 */
class BuildingCost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%building_cost}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cost', 'building_id', 'resource_id'], 'required'],
            [['cost', 'building_id', 'resource_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cost' => 'Cost',
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
