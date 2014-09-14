<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 */
class GameAsset extends AssetBundle
{
  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    'game/stylesheets/screen.css',
  ];
  public $js = [
  ];
  public $depends = [
//    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
  ];
}
