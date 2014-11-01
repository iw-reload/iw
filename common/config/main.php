<?php
return [
  'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
  'components' => [
    'authManager' => [
      'class' => 'yii\rbac\DbManager',
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'time' => [
      'class' => 'common\components\TimeComponent',
    ],
    'universe' => [
      'class' => 'common\components\universe\UniverseComponent',
    ],
  ],
];
