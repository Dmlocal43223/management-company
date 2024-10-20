<?php

namespace frontend\controllers;

use Exception;
use src\notification\entities\Notification;
use src\notification\repositories\NotificationRepository;
use src\notification\repositories\NotificationTypeRepository;
use src\notification\services\NotificationService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NotificationController extends Controller
{
    private NotificationRepository $notificationRepository;
    private NotificationService $notificationService;

    public function __construct($id, $module, $config = [])
    {
        $this->notificationRepository = new NotificationRepository();
        $this->notificationService = new NotificationService($this->notificationRepository, new NotificationTypeRepository());

        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->notificationRepository->getByUser(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id): string
    {
        $model = $this->findModel($id);

        try {
            if (!$model->isRead()) {
                $this->notificationService->read($model);
            }
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModel(int $id): Notification
    {
        $model = $this->notificationRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}