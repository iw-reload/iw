<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\GameAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

// TODO have a look at assets. Don't use the site.css, but a game.css.
// TODO have a look at less/sass to create game.css
GameAsset::register($this);
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
    
    <div class="container">
        <div class="row">
            <div class="col-sm-2">
              <!--Sidebar content-->
              <?php
                $css = <<<EOT
li.disabled a {
  pointer-events: none;
  cursor: default;
  color: red;
}
EOT;
                $this->registerCss($css);
                echo Menu::widget([
                    'items' => [
                        // Important: you need to specify url as 'controller/action',
                        // not just as 'controller' even if default action is used.
                        ['label' => 'Startseite', 'url' => ['game/index']],
                        ['label' => 'Bauen', 'url' => ['game/construct']],
                        ['label' => 'Forschung', 'url' => ['game/research'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Schiffbau', 'url' => ['game/ships'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Verteidigung', 'url' => ['game/defense'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Handel', 'url' => ['game/trade'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Wirtschaft', 'url' => ['game/economy'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Flotten', 'url' => ['game/fleets'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Militär', 'url' => ['game/military'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Universum', 'url' => ['game/universe'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Simulator', 'url' => ['game/simulator'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Nachrichten', 'url' => ['game/messages'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Herrscherinfo', 'url' => ['game/user-info'], 'options' => ['class' => 'disabled']],
                        ['label' => 'IW-News', 'url' => ['game/news'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Allianzen', 'url' => ['game/alliance'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Highscore', 'url' => ['game/highscore'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Tools', 'url' => ['game/tools'], 'options' => ['class' => 'disabled']],
                        ['label' => 'Einstellungen', 'url' => ['game/settings'], 'options' => ['class' => 'disabled']],
                        ['label' => 'IW-Doku', 'url' => 'http://doku.icewars.de'],
                        [
                            'label' => 'Logout',
                            'url' => ['game/logout'],
                            'template' => '<a href="{url}" data-method="post">{label}</a>',
                            'visible' => !Yii::$app->user->isGuest,
                        ],
                    ],
                ]);              
              ?>
              
              <form>
                <select>
                  <option>[1:2:3] Kolonie von Ben</option>
                </select>
                <select>
                  <option>---</option>
                </select>
              </form>
              
            </div>
            <div class="col-sm-10">
                  <!--Body content-->
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>    

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
