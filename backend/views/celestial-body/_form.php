<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CelestialBody */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="celestial-body-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pos_galaxy')->textInput() ?>

    <?= $form->field($model, 'pos_system')->textInput() ?>

    <?= $form->field($model, 'pos_planet')->textInput() ?>

    <?= $form->field($model, 'density_iron')->textInput() ?>

    <?= $form->field($model, 'density_chemicals')->textInput() ?>

    <?= $form->field($model, 'density_ice')->textInput() ?>

    <?= $form->field($model, 'gravity')->textInput() ?>

    <?= $form->field($model, 'living_conditions')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
