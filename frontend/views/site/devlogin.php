<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Development Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
      During development, you can login as any of the registered users:
    </p>

    <div class="row">
        <div class="col-lg-5">
          
          <?php $form = ActiveForm::begin(['id' => 'form-dev-login']); ?>
            <?= $form->field($model, 'userName')->widget(
              AutoComplete::className(), [
                'clientOptions' => [
                  'source' => Url::to(['site/userlist']),
                ],
                'options' => [
                  'class' => 'form-control',
                ],
              ]
            ); ?>
            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
          <?php ActiveForm::end(); ?>
          
        </div>
    </div>
</div>
