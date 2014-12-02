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
    'db' => [
      'class' => 'yii\db\Connection',
    ],
    'doctrine' => [
      'class' => 'common\components\doctrine\DoctrineComponent',
      'connection' => [
        'params' => [],
      ],
      'entityManager' => [
        'config' => function() {
          $paths = [
            Yii::getAlias('@common/entities'),
          ];
          $isDevMode = YII_DEBUG;
          return \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration( $paths, $isDevMode );
        },
      ],
    ],
    'time' => [
      'class' => 'common\components\TimeComponent',
    ],
    'universe' => [
      'class' => 'common\components\universe\UniverseComponent',
    ],
  ],
];
