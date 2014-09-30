<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CelestialBodySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="celestial-body-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pos_galaxy') ?>

    <?= $form->field($model, 'pos_system') ?>

    <?= $form->field($model, 'pos_planet') ?>

    <?= $form->field($model, 'density_iron') ?>

    <?php // echo $form->field($model, 'density_chemicals') ?>

    <?php // echo $form->field($model, 'density_ice') ?>

    <?php // echo $form->field($model, 'gravity') ?>

    <?php // echo $form->field($model, 'living_conditions') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
