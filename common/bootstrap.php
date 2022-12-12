<?php

require(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
$dotenv->required([
    'MYSQL_DSN',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'REDIS_HOSTNAME',
    'DEBUG',
    'ENV',
]);

defined('YII_DEBUG') or define('YII_DEBUG', (bool)$_SERVER['DEBUG']);
defined('YII_ENV') or define('YII_ENV', $_SERVER['ENV']);

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

Yii::setAlias('market', dirname(__DIR__));
Yii::setAlias('storage', __DIR__ . '/../storage');
Yii::setAlias('storageUrl', '/storage');
Yii::setAlias('static', __DIR__ . '/../static');
Yii::setAlias('staticUrl', '/static');
