<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Building */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Buildings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
    ]) ?>

</div>
