<?php

namespace backend\controllers;

use Exception;
use src\file\entities\File;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FileController extends Controller
{
    public FileRepository $fileRepository;
    public function __construct($id, $module, $config = [])
    {
        $this->fileRepository = new FileRepository();

        parent::__construct($id, $module, $config);
    }
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->fileRepository->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        $referer = Yii::$app->request->headers->get('Referer');
        return $this->redirect($referer);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->fileRepository->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        $referer = Yii::$app->request->headers->get('Referer');
        return $this->redirect($referer);
    }

    protected function findModel(int $id): File
    {
        $model = $this->fileRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}