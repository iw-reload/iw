<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "building".
 *
 * @property integer $id
 * @property string $name
 *
 * @property BuildingCost[] $buildingCosts
 * @property BuildingProduction[] $buildingProductions
 * @property BuildingsOnBase[] $buildingsOnBases
 */
class Building extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%building}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingCosts()
    {
        return $this->hasMany(BuildingCost::className(), ['building_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingProductions()
    {
        return $this->hasMany(BuildingProduction::className(), ['building_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingsOnBases()
    {
        return $this->hasMany(BuildingsOnBase::className(), ['building_id' => 'id']);
    }
}
