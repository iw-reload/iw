<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
  
  <h1><?= Html::encode($this->title) ?></h1>

  <?php if ($model->isAuthenticated()): ?>
  <p>
    Hello <?= $model->getExternalUserName() ?>! You authenticated using
    <?= $model->getAuthProviderTitle() ?>.
    Please select a name for your IW-Account.
  </p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= $form->field($model, 'username') ?>
        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
  <?php else: ?>
  <p>
    Oops. Seems you're not logged in yet. Don't be confused, we're just doing
    things a little bit different around here. So please, first visit the
    <?= Html::a('login page', ['site/login']) ?>. After you've logged in,
    register your account. *g*
  </p>
  <?php endif; ?>

</div>
