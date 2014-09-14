<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Building */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'image')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cost_iron')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_steel')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_chemicals')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_vv4a')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_ice')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_water')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_energy')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_people')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_credits')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'cost_time')->textInput() ?>

    <?= $form->field($model, 'balance_iron')->textInput() ?>

    <?= $form->field($model, 'balance_steel')->textInput() ?>

    <?= $form->field($model, 'balance_chemicals')->textInput() ?>

    <?= $form->field($model, 'balance_vv4a')->textInput() ?>

    <?= $form->field($model, 'balance_ice')->textInput() ?>

    <?= $form->field($model, 'balance_water')->textInput() ?>

    <?= $form->field($model, 'balance_energy')->textInput() ?>

    <?= $form->field($model, 'balance_people')->textInput() ?>

    <?= $form->field($model, 'balance_credits')->textInput() ?>

    <?= $form->field($model, 'balance_satisfaction')->textInput() ?>

    <?= $form->field($model, 'highscore_points')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
