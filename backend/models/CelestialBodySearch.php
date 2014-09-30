<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CelestialBody;

/**
 * CelestialBodySearch represents the model behind the search form about `common\models\CelestialBody`.
 */
class CelestialBodySearch extends CelestialBody
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pos_galaxy', 'pos_system', 'pos_planet'], 'integer'],
            [['density_iron', 'density_chemicals', 'density_ice', 'gravity', 'living_conditions'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CelestialBody::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'pos_galaxy' => $this->pos_galaxy,
            'pos_system' => $this->pos_system,
            'pos_planet' => $this->pos_planet,
            'density_iron' => $this->density_iron,
            'density_chemicals' => $this->density_chemicals,
            'density_ice' => $this->density_ice,
            'gravity' => $this->gravity,
            'living_conditions' => $this->living_conditions,
        ]);

        return $dataProvider;
    }
}
