<?php

use yii\console\controllers\MigrateController;
use yii\log\FileTarget;

$config = [
    'id' => 'market-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'market\console\controllers',
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationNamespaces' => [
                'market\console\migrations',
            ],
            'migrationPath' => null,
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                'error' => [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/console.error.log',
                ],
            ],
        ],
    ],
];

return $config;
