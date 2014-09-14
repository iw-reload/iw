<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../common/config/main.php'),
    require(__DIR__ . '/../../../common/config/main-local.php'),
    require(__DIR__ . '/../../config/main.php'),
    require(__DIR__ . '/../../config/main-local.php'),
    [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=iw_reload_unit',
            ],
        ],
        'controllerMap' => [
            'fixture' => [
                'class' => 'yii\faker\FixtureController',
                'fixtureDataPath' => '@console/tests/unit/fixtures/data',
                'templatePath' => '@common/tests/templates/fixtures'
            ],
        ],
    ]
);
