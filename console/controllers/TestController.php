<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class TestController extends Controller
{
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