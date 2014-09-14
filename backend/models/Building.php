<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "building".
 *
 * @property string $id
 * @property string $group
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $cost_iron
 * @property string $cost_steel
 * @property string $cost_chemicals
 * @property string $cost_vv4a
 * @property string $cost_ice
 * @property string $cost_water
 * @property string $cost_energy
 * @property string $cost_people
 * @property string $cost_credits
 * @property string $cost_time
 * @property integer $balance_iron
 * @property integer $balance_steel
 * @property integer $balance_chemicals
 * @property integer $balance_vv4a
 * @property integer $balance_ice
 * @property integer $balance_water
 * @property integer $balance_energy
 * @property integer $balance_people
 * @property integer $balance_credits
 * @property double $balance_satisfaction
 * @property integer $highscore_points
 * @property string $modified
 *
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
            [['group', 'name', 'image', 'description', 'cost_iron', 'cost_steel', 'cost_chemicals', 'cost_vv4a', 'cost_ice', 'cost_water', 'cost_energy', 'cost_people', 'cost_credits', 'cost_time', 'balance_iron', 'balance_steel', 'balance_chemicals', 'balance_vv4a', 'balance_ice', 'balance_water', 'balance_energy', 'balance_people', 'balance_credits', 'balance_satisfaction', 'highscore_points'], 'required'],
            [['image', 'description'], 'string'],
            [['cost_iron', 'cost_steel', 'cost_chemicals', 'cost_vv4a', 'cost_ice', 'cost_water', 'cost_energy', 'cost_people', 'cost_credits', 'balance_iron', 'balance_steel', 'balance_chemicals', 'balance_vv4a', 'balance_ice', 'balance_water', 'balance_energy', 'balance_people', 'balance_credits', 'highscore_points'], 'integer'],
            [['cost_time', 'modified'], 'safe'],
            [['balance_satisfaction'], 'number'],
            [['group', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group' => 'Group',
            'name' => 'Name',
            'image' => 'Image',
            'description' => 'Description',
            'cost_iron' => 'Cost Iron',
            'cost_steel' => 'Cost Steel',
            'cost_chemicals' => 'Cost Chemicals',
            'cost_vv4a' => 'Cost Vv4a',
            'cost_ice' => 'Cost Ice',
            'cost_water' => 'Cost Water',
            'cost_energy' => 'Cost Energy',
            'cost_people' => 'Cost People',
            'cost_credits' => 'Cost Credits',
            'cost_time' => 'Cost Time',
            'balance_iron' => 'Balance Iron',
            'balance_steel' => 'Balance Steel',
            'balance_chemicals' => 'Balance Chemicals',
            'balance_vv4a' => 'Balance Vv4a',
            'balance_ice' => 'Balance Ice',
            'balance_water' => 'Balance Water',
            'balance_energy' => 'Balance Energy',
            'balance_people' => 'Balance People',
            'balance_credits' => 'Balance Credits',
            'balance_satisfaction' => 'Balance Satisfaction',
            'highscore_points' => 'Highscore Points',
            'modified' => 'Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildingsOnBases()
    {
        return $this->hasMany(BuildingsOnBase::className(), ['building_id' => 'id']);
    }
}
