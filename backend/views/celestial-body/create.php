<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CelestialBody */

$this->title = 'Create Celestial Body';
$this->params['breadcrumbs'][] = ['label' => 'Celestial Bodies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="celestial-body-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
