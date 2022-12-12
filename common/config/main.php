<?php

use market\common\components\FileStorageComponent;
use market\common\components\Formatter;
use market\common\components\SentryTarget;
use market\common\components\ServicesDefinitions;
use yii\db\Connection as DbConnection;
use yii\queue\redis\Queue;
use yii\redis\Cache as RedisCache;
use yii\redis\Connection as RedisConnection;

return [
    'bootstrap' => ['log', 'queue', ServicesDefinitions::class],
    'runtimePath' => dirname(__DIR__, 2) . '/runtime',
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'extensions' => require(dirname(__DIR__, 2) . '/vendor/yiisoft/extensions.php'),
    'aliases' => [
        '@bower' => dirname(__DIR__, 2) . '/vendor/bower-asset',
    ],
    'components' => [
        'db' => [
            'class' => DbConnection::class,
            'charset' => 'utf8',
            'dsn' => $_SERVER['MYSQL_DSN'],
            'username' => $_SERVER['MYSQL_USER'],
            'password' => $_SERVER['MYSQL_PASSWORD'],
            'enableSchemaCache' => !YII_DEBUG,
            'enableProfiling' => YII_DEBUG,
            'enableLogging' => YII_DEBUG,
        ],
        'redis' => [
            'class' => RedisConnection::class,
            'hostname' => $_SERVER['REDIS_HOSTNAME'],
        ],
        'cache' => [
            'class' => RedisCache::class,
            'redis' => 'redis',
            'keyPrefix' => 'market:'
        ],
        'queue' => [
            'class' => Queue::class,
            'redis' => 'redis',
        ],
        'formatter' => [
            'class' => Formatter::class,
        ],
        'fileStorage' => [
            'class' => FileStorageComponent::class,
            'adapters' => [],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 1,
            'targets' => [
                'sentry' => [
                    'class' => SentryTarget::class,
                    'dsn' => $_SERVER['SENTRY_DSN'],
                    'levels' => ['error', 'warning'],
                    'release' => $_SERVER['VERSION'],
                    'enabled' => !YII_DEBUG,
                ],
            ],
        ],
    ],
];
