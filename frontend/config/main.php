<?php
$params = array_merge(
  require(__DIR__ . '/../../common/config/params.php'),
  require(__DIR__ . '/../../common/config/params-local.php'),
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);

Yii::$container->setSingleton( 'frontend\interfaces\BuildingFinderInterface', 'frontend\components\building\BuildingComponent' );

return [
  'id' => 'app-frontend',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerNamespace' => 'frontend\controllers',
  'layout' => 'game',
  'components' => [
    'authClientCollection' => [
      'class' => 'yii\authclient\Collection',
      'clients' => [
//        'google' => [
//          'class' => 'yii\authclient\clients\GoogleOpenId',
//        ],
        'github' => [
          'class' => 'yii\authclient\clients\GitHub',
          'clientId' => '3e037dd52fea20c19267',
          'scope' => '',
        ],
      ],
    ],    
    'building' => [
      'class' => 'frontend\components\building\BuildingComponent',
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
    'log' => [
      'traceLevel' => YII_DEBUG ? 3 : 0,
      'targets' => [
        [
          'class' => 'yii\log\FileTarget',
          'levels' => ['error', 'warning'],
        ],
      ],
    ],
    'universe' => [
      'class' => 'common\components\universe\UniverseComponent',
    ],
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => true,
      'returnUrl' => ['game'],
    ],
  ],
  'controllerMap' => [
    'site' => [
      'class' => 'frontend\controllers\SiteController',
      'layout' => 'main',
    ],
  ],
  'params' => $params,
];
