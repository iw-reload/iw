<?php
$params = array_merge(
  require(__DIR__ . '/../../common/config/params.php'),
  require(__DIR__ . '/../../common/config/params-local.php'),
  require(__DIR__ . '/params.php'),
  require(__DIR__ . '/params-local.php')
);

Yii::$container->setSingleton( 'frontend\interfaces\BuildingFinderInterface', function () {
  return Yii::$app->get('building');
});

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
        'github' => [
          'class' => 'yii\authclient\clients\GitHub',
          'clientId' => '3e037dd52fea20c19267',
          'scope' => '',
          'normalizeUserAttributeMap' => [
            'name' => 'login',
          ],
        ],
        'google' => [
          'class' => 'yii\authclient\clients\GoogleOAuth',
          'clientId' => '741150207793-ufa33kfdcpquau3tsrsb9lb0gs9ceg66.apps.googleusercontent.com',
          'scope' => 'profile',
          'normalizeUserAttributeMap' => [
            'name' => 'displayName',
          ],
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
    'task' => [
      'class' => 'frontend\components\task\TaskComponent',
    ],
    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'rules' => [
        'oauth2callback/<authclient:google>' => 'site/auth',
      ],
    ],
    'user' => [
      'identityClass' => 'common\models\User',
      'enableAutoLogin' => false,
      'returnUrl' => ['game'],
    ],
  ],
  'controllerMap' => [
    'site' => [
      'class' => 'frontend\controllers\SiteController',
      'layout' => 'main',
    ],
  ],
  'on beforeAction' => function ($event) {
    // preload important components
    \Yii::$app->get('task');
  },
//  'as BaseManagerInitializer' => [
//    'class' => 'frontend\components\baseManager\Initializer',
//  ],
//  'as UserManagerInitializer' => [
//    'class' => 'frontend\components\userManager\Initializer',
//  ],
  'params' => $params,
];
