<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'Control panel',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'control-panel/main',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'UZ4iKz32T9bHX5fjge7iHn2E1lpX4HbK',
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
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        // 'urlManager' => [
        //     'enablePrettyUrl' => true,
        //     'enableStrictParsing' => false,
        //     'showScriptName' => false,
        //     'rules' => [
        //         [
        //             'pattern' => '',
        //             'route' => 'site/index',
        //         ],
        //         [
        //             'pattern' => 'admin',
        //             'route' => 'admin'
        //         ],
        //         ['class' => 'app\components\MUrlRule'],
        //     ],
        // ],        

    ],
    'modules' => [
        'redactor' => 'app\modules\redactor\RedactorModule',
    ],

    'params' => $params,
    'language' => 'ru',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
