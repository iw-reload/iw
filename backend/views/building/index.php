<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BuildingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Buildings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Building', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'group',
            'name',
            'image:ntext',
            'description:ntext',
            'cost_iron',
            'cost_steel',
            'cost_chemicals',
            'cost_vv4a',
            'cost_ice',
            'cost_water',
            'cost_energy',
            'cost_people',
            'cost_credits',
            'cost_time',
            'balance_iron',
            'balance_steel',
            'balance_chemicals',
            'balance_vv4a',
            'balance_ice',
            'balance_water',
            'balance_energy',
            'balance_people',
            'balance_credits',
            'balance_satisfaction',
            'highscore_points',
            'modified',
        ],
    ]); ?>

</div>
