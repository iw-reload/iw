<?php

namespace common\models;

use yii\helpers\Inflector;
use Yii;

/**
 * This is the model class for table "{{%building}}".
 *
 * @property integer $id
 * @property string $group
 * @property string $name
 * @property string $image
 * @property string $description
 * @property integer $cost_iron
 * @property integer $cost_steel
 * @property integer $cost_chemicals
 * @property integer $cost_vv4a
 * @property integer $cost_ice
 * @property integer $cost_water
 * @property integer $cost_energy
 * @property integer $cost_people
 * @property integer $cost_credits
 * @property string $cost_time
 * @property integer $balance_iron
 * @property integer $balance_steel
 * @property integer $balance_chemicals
 * @property integer $balance_vv4a
 * @property integer $balance_ice
 * @property integer $balance_water
 * @property integer $balance_energy
 * @property integer $balance_people
 * @property double $balance_credits
 * @property double $balance_satisfaction
 * @property integer $storage_chemicals
 * @property integer $storage_ice_and_water
 * @property integer $storage_energy
 * @property integer $shelter_iron
 * @property integer $shelter_steel
 * @property integer $shelter_chemicals
 * @property integer $shelter_vv4a
 * @property integer $shelter_ice_and_water
 * @property integer $shelter_energy
 * @property integer $shelter_people
 * @property integer $highscore_points
 * @property string $modified
 *
 * @property BuildingsOnBase[] $buildingsOnBases
 * 
 * @translatable $name
 * @translatable $description
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
      [['group', 'name', 'description', 'cost_iron', 'cost_steel', 'cost_chemicals', 'cost_vv4a', 'cost_ice', 'cost_water', 'cost_energy', 'cost_people', 'cost_credits', 'cost_time', 'balance_iron', 'balance_steel', 'balance_chemicals', 'balance_vv4a', 'balance_ice', 'balance_water', 'balance_energy', 'balance_people', 'balance_credits', 'balance_satisfaction', 'storage_chemicals', 'storage_ice_and_water', 'storage_energy', 'shelter_iron', 'shelter_steel', 'shelter_chemicals', 'shelter_vv4a', 'shelter_ice_and_water', 'shelter_energy', 'shelter_people', 'highscore_points'], 'required'],
      [['image', 'description'], 'string'],
      [['cost_iron', 'cost_steel', 'cost_chemicals', 'cost_vv4a', 'cost_ice', 'cost_water', 'cost_energy', 'cost_people', 'cost_credits', 'balance_iron', 'balance_steel', 'balance_chemicals', 'balance_vv4a', 'balance_ice', 'balance_water', 'balance_energy', 'balance_people', 'storage_chemicals', 'storage_ice_and_water', 'storage_energy', 'shelter_iron', 'shelter_steel', 'shelter_chemicals', 'shelter_vv4a', 'shelter_ice_and_water', 'shelter_energy', 'shelter_people', 'highscore_points'], 'integer'],
      [['cost_time', 'modified'], 'safe'],
      [['balance_credits', 'balance_satisfaction'], 'number'],
      [['group', 'name'], 'string', 'max' => 255],

      [['image'], 'filter', 'filter' => function() { return '@buildingImages/' . Inflector::slug($this->name) . '.png'; }],
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
            'cost_time' => 'Cost Time (hh:mm:ss)',
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
            'storage_chemicals' => 'Storage Chemicals',
            'storage_ice_and_water' => 'Storage Ice And Water',
            'storage_energy' => 'Storage Energy',
            'shelter_iron' => 'Shelter Iron',
            'shelter_steel' => 'Shelter Steel',
            'shelter_chemicals' => 'Shelter Chemicals',
            'shelter_vv4a' => 'Shelter Vv4a',
            'shelter_ice_and_water' => 'Shelter Ice And Water',
            'shelter_energy' => 'Shelter Energy',
            'shelter_people' => 'Shelter People',
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
