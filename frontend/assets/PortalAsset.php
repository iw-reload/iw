<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 */
class PortalAsset extends AssetBundle
{
  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    'portal/stylesheets/screen.css',
  ];
  public $js = [
  ];
  public $depends = [
//    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset',
  ];
}
