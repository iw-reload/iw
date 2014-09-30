<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Building */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'group')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'image')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cost_iron')->textInput() ?>

    <?= $form->field($model, 'cost_steel')->textInput() ?>

    <?= $form->field($model, 'cost_chemicals')->textInput() ?>

    <?= $form->field($model, 'cost_vv4a')->textInput() ?>

    <?= $form->field($model, 'cost_ice')->textInput() ?>

    <?= $form->field($model, 'cost_water')->textInput() ?>

    <?= $form->field($model, 'cost_energy')->textInput() ?>

    <?= $form->field($model, 'cost_people')->textInput() ?>

    <?= $form->field($model, 'cost_credits')->textInput() ?>

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

    <?= $form->field($model, 'storage_chemicals')->textInput() ?>

    <?= $form->field($model, 'storage_ice_and_water')->textInput() ?>

    <?= $form->field($model, 'storage_energy')->textInput() ?>

    <?= $form->field($model, 'shelter_iron')->textInput() ?>

    <?= $form->field($model, 'shelter_steel')->textInput() ?>

    <?= $form->field($model, 'shelter_chemicals')->textInput() ?>

    <?= $form->field($model, 'shelter_vv4a')->textInput() ?>

    <?= $form->field($model, 'shelter_ice_and_water')->textInput() ?>

    <?= $form->field($model, 'shelter_energy')->textInput() ?>

    <?= $form->field($model, 'shelter_people')->textInput() ?>

    <?= $form->field($model, 'highscore_points')->textInput() ?>

    <?= $form->field($model, 'modified')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
