<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
          NavBar::begin([
            'brandLabel' => 'IceWars Backend',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
              'class' => 'navbar-inverse navbar-fixed-top',
            ],
          ]);
          $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Frontend', 'url' => '../../frontend/web/index.php?r=game'],
          ];
          if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            if (array_search(Yii::$app->request->getUserIP(),['127.0.0.1','::1'],true) !== false) {
              $menuItems[] = ['label' => 'Dev Login', 'url' => ['/site/dev-login']];
            }
          } else {
            $menuItems[] = [
              'label' => 'Logout (' . Yii::$app->user->identity->name . ')',
              'url' => ['/site/logout'],
              'linkOptions' => ['data-method' => 'post']
            ];
          }
          echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
          ]);
          NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; IceWars Reload Team <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
