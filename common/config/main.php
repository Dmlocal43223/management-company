<?php
return [
    'name' => 'Управляющая компания',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'common\bootstrap\SetUp',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => '{{%auth_items}}',
            'itemChildTable' => '{{%auth_item_children}}',
            'assignmentTable' => '{{%auth_assignments}}',
            'ruleTable' => '{{%auth_rules}}',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'management-company-redis-1',
                'port' => 6379,
                'database' => 0,
            ],
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => 'elasticsearch:9200'],
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'management-company-redis-1',
            'port' => 6379,
            'database' => 0,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
                'booleanFormat' => ['Нет', 'Да'],
                'locale' => 'ru-RU',
                'dateFormat' => 'php:d.m.Y',
                'datetimeFormat' => 'php:d.m.Y H:i',
                'timeFormat' => 'php:H:i',
        ],
    ],
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module']
    ]
];
