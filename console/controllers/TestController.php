<?php

namespace console\controllers;

use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use src\news\entities\News;
use src\role\entities\Role;
use Yii;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionModel()
    {
        $news = News::findOne(4);
        $previewFile = (new FileRepository())->findFileByTypeForNews($news, FileType::PREVIEW_TYPE_ID);
        dd($previewFile);

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