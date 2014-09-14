<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BuildingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'group') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'cost_iron') ?>

    <?php // echo $form->field($model, 'cost_steel') ?>

    <?php // echo $form->field($model, 'cost_chemicals') ?>

    <?php // echo $form->field($model, 'cost_vv4a') ?>

    <?php // echo $form->field($model, 'cost_ice') ?>

    <?php // echo $form->field($model, 'cost_water') ?>

    <?php // echo $form->field($model, 'cost_energy') ?>

    <?php // echo $form->field($model, 'cost_people') ?>

    <?php // echo $form->field($model, 'cost_credits') ?>

    <?php // echo $form->field($model, 'cost_time') ?>

    <?php // echo $form->field($model, 'balance_iron') ?>

    <?php // echo $form->field($model, 'balance_steel') ?>

    <?php // echo $form->field($model, 'balance_chemicals') ?>

    <?php // echo $form->field($model, 'balance_vv4a') ?>

    <?php // echo $form->field($model, 'balance_ice') ?>

    <?php // echo $form->field($model, 'balance_water') ?>

    <?php // echo $form->field($model, 'balance_energy') ?>

    <?php // echo $form->field($model, 'balance_people') ?>

    <?php // echo $form->field($model, 'balance_credits') ?>

    <?php // echo $form->field($model, 'balance_satisfaction') ?>

    <?php // echo $form->field($model, 'highscore_points') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
