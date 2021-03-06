<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'teleport',
    'name' => 'Сотовая связь',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => '/mobile/number/index',
    'aliases' => [
        '@mobile' => '@app/modules/mobile',
        '@directory' => '@app/modules/directory',
    ],
    'modules' => [
        'mobile' => [
            'class' => 'app\modules\mobile\Module',
        ],
        'directory' => [
            'class' => 'app\modules\directory\Module',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'osGV00iXGwBxJ7iSNoAVgvvsiz8YE2JQ',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'yii\swiftmailer\Mailer',
                'useFileTransport' => true,
            ],
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
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.99.*'],
        'panels' => [
            'mongodb' => [
                'class' => 'yii\mongodb\debug\MongoDbPanel',
            ],
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.99.*']
    ];

}

return $config;
