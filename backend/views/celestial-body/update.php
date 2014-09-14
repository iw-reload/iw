<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CelestialBody */

$this->title = 'Update Celestial Body: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Celestial Bodies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="celestial-body-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
