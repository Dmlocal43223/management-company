<?php

namespace frontend\controllers;

use src\file\repositories\FileRepository;
use src\news\entities\News;
use src\news\repositories\NewsRepository;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    private NewsRepository $newsRepository;
    private FileRepository $fileRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->fileRepository = new FileRepository();
        $this->newsRepository = new NewsRepository();

        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all News models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->newsRepository->getQuery(),
            'pagination' => [
                'pageSize' => 25
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $photos = $this->fileRepository->findPhotosByNews($model);
        $documents = $this->fileRepository->findDocumentsByNews($model);

        return $this->render('view', [
            'model' => $model,
            'photos' => ArrayHelper::getColumn($photos, 'source'),
            'documents' => ArrayHelper::getColumn($documents, 'source')
        ]);
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): News
    {
        $model = $this->newsRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
