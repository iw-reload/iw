<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "buildings_on_base".
 *
 * @property integer $id
 * @property integer $base_id
 * @property integer $buildings_count
 * @property integer $building_id
 *
 * @property Base $base
 * @property Building $building
 */
class BuildingsOnBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%buildings_on_base}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['base_id', 'buildings_count', 'building_id'], 'required'],
            [['base_id', 'buildings_count', 'building_id'], 'integer']
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
            'buildings_count' => 'Buildings Count',
            'building_id' => 'Building ID',
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
    public function getBuilding()
    {
        return $this->hasOne(Building::className(), ['id' => 'building_id']);
    }
}
