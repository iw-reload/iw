<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "resource".
 *
 * @property integer $id
 * @property string $name
 * @property integer $group
 * @property integer $display_order
 *
 * @property BuildingCost[] $buildingCosts
 * @property BuildingProduction[] $buildingProductions
 * @property StoredResources[] $storedResources
 */
class Resource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resource}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'group', 'display_order'], 'required'],
            [['group', 'display_order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['display_order'], 'unique']
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
            'group' => 'Group',
            'display_order' => 'Display Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingCosts()
    {
        return $this->hasMany(BuildingCost::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingProductions()
    {
        return $this->hasMany(BuildingProduction::className(), ['resource_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoredResources()
    {
        return $this->hasMany(StoredResources::className(), ['resource_id' => 'id']);
    }
}
