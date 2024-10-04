<?php

namespace console\controllers;

use src\role\entities\Role;
use Yii;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionModel()
    {
        dd(Yii::$app->authManager);

        dd(class_exists('src\role\entities\Role'));
//        dd(class_exists('Role'));
        $role = Role::findOne(1);
        dd($role);
        $role->name = 'test4';

        $role->save();
    }

    public function actionRedis()
    {
        Yii::$app->cache->set('test-key', 'test-value', 3600);
        $value = Yii::$app->cache->get('test-key');

        if ($value) {
            echo "Cache is working! Value: " . $value;
        } else {
            echo "Failed to retrieve value from cache.";
        }

    }
}