<?php
/**
 * Application configuration for console unit tests
 */
return yii\helpers\ArrayHelper::merge(
  require(YII_APP_BASE_PATH . '/common/config/main.php'),
  require(YII_APP_BASE_PATH . '/common/config/main-local.php'),
  require(YII_APP_BASE_PATH . '/console/config/main.php'),
  require(YII_APP_BASE_PATH . '/console/config/main-local.php'),
  require(dirname(__DIR__) . '/config.php'),
  require(dirname(__DIR__) . '/unit.php'),
  [
    'components' => [
      'authManager' => [
        'class' => 'yii\rbac\PhpManager',
        'assignmentFile' => '@tests/codeception/console/rbac/assignments.php',
        'itemFile' => '@tests/codeception/console/rbac/items.php',
        'ruleFile' => '@tests/codeception/console/rbac/rules.php',
      ],
    ],
    'controllerMap' => [
      'doctrine' => [
        'class' => 'common\components\doctrine\console\DoctrineController',
      ],
    ],
  ]
);
