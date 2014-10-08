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
          'clientId' => '5f1b3ddc34f0bae2cef2',
          'scope' => '',
          'normalizeUserAttributeMap' => [
            'name' => 'login',
          ],
        ],
        'google' => [
          'class' => 'yii\authclient\clients\GoogleOAuth',
          'clientId' => '228423791479-2opkorudrgtntusni3c4lfb5ej1u79s4.apps.googleusercontent.com',
          'scope' => 'profile',
          'normalizeUserAttributeMap' => [
            'name' => 'displayName',
          ],
        ],
      ],
    ],    
    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
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
