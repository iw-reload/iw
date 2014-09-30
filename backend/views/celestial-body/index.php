<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CelestialBodySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Celestial Bodies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="celestial-body-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Celestial Body', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'pos_galaxy',
            'pos_system',
            'pos_planet',
            'density_iron',
            'density_chemicals',
            'density_ice',
            'gravity',
            'living_conditions',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
