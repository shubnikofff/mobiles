<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@unit' => '@app/tests/codeception/unit',
    ],
    'controllerMap' => [
        'mongodb-migrate' => 'yii\mongodb\console\controllers\MigrateController',
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'app\tests\codeception\unit\fixtures'
        ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
        'datatransfer' => [
            'class' => 'app\modules\datatransfer\Module',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=teleport;',
            'username' => 'teleport',
            'password' => 'MysqlTeleport',
            'charset' => 'utf8',
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/teleport',
        ],
    ],
    'params' => $params,
];
