<?php

namespace backend\controllers;

use backend\forms\NewsFileForm;
use backend\forms\NewsForm;
use backend\forms\search\NewsSearchForm;
use Exception;
use src\file\repositories\FileRepository;
use src\file\repositories\NewsFileRepository;
use src\file\services\FileService;
use src\news\entities\News;
use src\news\repositories\NewsRepository;
use src\news\services\NewsService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    private NewsService $newsService;
    private NewsRepository $newsRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->newsRepository = new NewsRepository();
        $fileRepository = new FileRepository();
        $newsFileRepository = new NewsFileRepository();
        $fileService = new FileService($fileRepository);

        $this->newsService = new NewsService(
            $fileService,
            $this->newsRepository,
            $newsFileRepository,
            $fileRepository
        );

        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritDoc
     */
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

    /**
     * Lists all News models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new NewsSearchForm();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->newsRepository->getNoResultsQuery();
        } else {
            $query = $this->newsRepository->getFilteredQuery($searchModel);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
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
        $fileModel = new NewsFileForm();

        $fileDataProvider = new ArrayDataProvider([
            'allModels' => (new NewsFileRepository())->findFilesByNews($model),
            'pagination' => false
        ]);

        return $this->render('view', [
            'model' => $model,
            'fileModel' => $fileModel,
            'fileDataProvider' => $fileDataProvider
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $newsModel = new NewsForm();
        $fileModel = new NewsFileForm();

        if ($newsModel->load(Yii::$app->request->post()) && $fileModel->load(Yii::$app->request->post())) {
            $fileModel->setUploadedFiles();
            if ($newsModel->validate() && $fileModel->validate()) {
                try {
                    $news = $this->newsService->create($newsModel, $fileModel);

                    return $this->redirect(['view', 'id' => $news->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('create', [
            'newsModel' => $newsModel,
            'fileModel' => $fileModel,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $news = $this->findModel($id);
        $form = new NewsForm();
        $form->setAttributes($news->getAttributes());

        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                try {
                    $this->newsService->edit($news, $form);

                    return $this->redirect(['view', 'id' => $news->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('update', [
            'news' => $news,
            'newsModel' => $form,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->newsService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->newsService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionUpload(int $id): Response|string
    {
        $model = $this->findModel($id);
        $fileModel = new NewsFileForm();

        if ($fileModel->load(Yii::$app->request->post())) {
            $fileModel->setUploadedFiles();
            if ($fileModel->validate()) {
                try {
                    $this->newsService->uploadFile($model, $fileModel);
                    Yii::$app->session->setFlash('success', 'Файлы успешно загружены.');
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->redirect(['view', 'id' => $id]);
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
