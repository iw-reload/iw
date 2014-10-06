<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
      You can login using one of the following external authentication
      providers.
    </p>

    <div class="row">
        <div class="col-lg-5">
          
          <?= yii\authclient\widgets\AuthChoice::widget([
               'baseAuthUrl' => ['site/auth']
          ]) ?>
          
        </div>
    </div>
</div>
