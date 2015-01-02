<?php
/**
 * Application configuration shared by all applications and test types
 */
return [
    'components' => [
        'db' => [
            'dsn' => 'sqlite:'. \Yii::getAlias('@common/data/iw_tests.sqlite'),
        ],
        'doctrine' => [
          'class' => 'common\components\doctrine\DoctrineComponent',
          'connection' => [
            'params' => [
              'driver' => 'pdo_sqlite',
              'path' => \Yii::getAlias('@common/data/iw_tests.sqlite'),
            ],
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
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];
