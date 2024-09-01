<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'common\bootstrap\SetUp',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'management-company-redis-1',
                'port' => 6379,
                'database' => 0,
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'management-company-redis-1',
            'port' => 6379,
            'database' => 0,
        ],
    ],
];
