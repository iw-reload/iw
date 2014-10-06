<?php
$params = array_merge(
  require(__DIR__ . '/../../common/config/params.php'),
  require(__DIR__ . '/../../common/config/params-local.php'),
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);

return [
  'id' => 'app-backend',
  'basePath' => dirname(__DIR__),
  'controllerNamespace' => 'backend\controllers',
  'bootstrap' => ['log'],
  'modules' => [],
  'components' => [
    'authClientCollection' => [
      'class' => 'yii\authclient\Collection',
      'clients' => [
        'github' => [
          'class' => 'yii\authclient\clients\GitHub',
          'clientId' => '3e037dd52fea20c19267',
          'scope' => '',
        ],
      ],
    ],    
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => false,
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
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
  ],
  'params' => $params,
];
