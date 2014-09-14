<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Building;

/**
 * BuildingSearch represents the model behind the search form about `backend\models\Building`.
 */
class BuildingSearch extends Building
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cost_iron', 'cost_steel', 'cost_chemicals', 'cost_vv4a', 'cost_ice', 'cost_water', 'cost_energy', 'cost_people', 'cost_credits', 'balance_iron', 'balance_steel', 'balance_chemicals', 'balance_vv4a', 'balance_ice', 'balance_water', 'balance_energy', 'balance_people', 'balance_credits', 'highscore_points'], 'integer'],
            [['group', 'name', 'image', 'description', 'cost_time', 'modified'], 'safe'],
            [['balance_satisfaction'], 'number'],
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
        $query = Building::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'cost_iron' => $this->cost_iron,
            'cost_steel' => $this->cost_steel,
            'cost_chemicals' => $this->cost_chemicals,
            'cost_vv4a' => $this->cost_vv4a,
            'cost_ice' => $this->cost_ice,
            'cost_water' => $this->cost_water,
            'cost_energy' => $this->cost_energy,
            'cost_people' => $this->cost_people,
            'cost_credits' => $this->cost_credits,
            'cost_time' => $this->cost_time,
            'balance_iron' => $this->balance_iron,
            'balance_steel' => $this->balance_steel,
            'balance_chemicals' => $this->balance_chemicals,
            'balance_vv4a' => $this->balance_vv4a,
            'balance_ice' => $this->balance_ice,
            'balance_water' => $this->balance_water,
            'balance_energy' => $this->balance_energy,
            'balance_people' => $this->balance_people,
            'balance_credits' => $this->balance_credits,
            'balance_satisfaction' => $this->balance_satisfaction,
            'highscore_points' => $this->highscore_points,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
