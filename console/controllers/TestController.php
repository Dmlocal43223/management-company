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
    public function actionSend()
    {


        try {
            $sent = Yii::$app->mailer->compose()
                ->setFrom('goodworld724@gmail.com')
                ->setTo('goodworld724@gmail.com')
                ->setSubject('Тема письма')
                ->setTextBody('Текстовое содержание письма')
                ->setHtmlBody('<b>HTML-содержимое письма</b>')
                ->send();

            if (!$sent) {
                throw new \Exception('Message could not be sent.');
            }
        } catch (\Exception $e) {
            dd("Email sending error: " . $e->getMessage());
            // Дополнительно, вы можете вывести сообщение об ошибке на экран или логировать его
        }
        dd($sent);
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