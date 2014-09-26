<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
  'id' => 'app-console',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'controllerMap' => [
    'message' => [
      'class' => 'console\controllers\MessageController',
    ],
  ],
  'controllerNamespace' => 'console\controllers',
  'modules' => [],
  'components' => [
    'log' => [
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
  ],
  'params' => $params,
];
