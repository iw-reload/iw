<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "celestial_body".
 *
 * @property string $id
 * @property integer $pos_galaxy
 * @property integer $pos_system
 * @property integer $pos_planet
 * @property double $density_iron
 * @property double $density_chemicals
 * @property double $density_ice
 * @property double $gravity
 * @property double $living_conditions
 *
 * @property Base $base
 */
class CelestialBody extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%celestial_body}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_galaxy', 'pos_system', 'pos_planet', 'density_iron', 'density_chemicals', 'density_ice', 'gravity', 'living_conditions'], 'required'],
            [['pos_galaxy', 'pos_system', 'pos_planet'], 'integer'],
            [['density_iron', 'density_chemicals', 'density_ice', 'gravity', 'living_conditions'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pos_galaxy' => 'Pos Galaxy',
            'pos_system' => 'Pos System',
            'pos_planet' => 'Pos Planet',
            'density_iron' => 'Density Iron',
            'density_chemicals' => 'Density Chemicals',
            'density_ice' => 'Density Ice',
            'gravity' => 'Gravity',
            'living_conditions' => 'Living Conditions',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBase()
    {
        return $this->hasOne(Base::className(), ['id' => 'id']);
    }
}
