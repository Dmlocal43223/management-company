<?php

namespace backend\controllers;

use backend\forms\NewsFileForm;
use backend\forms\NewsForm;
use backend\forms\search\NewsSearch;
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
use yii\helpers\Html;
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
    private FileRepository $fileRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->newsRepository = new NewsRepository();
        $this->fileRepository = new FileRepository();
        $newsFileRepository = new NewsFileRepository();
        $fileService = new FileService($this->fileRepository);

        $this->newsService = new NewsService(
            $fileService,
            $this->newsRepository,
            $newsFileRepository,
            $this->fileRepository
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
        $searchModel = new NewsSearch();
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
        $fileForm = new NewsFileForm();

        $fileDataProvider = new ArrayDataProvider([
            'allModels' => $this->fileRepository->findFilesByNews($model),
            'pagination' => false
        ]);

        return $this->render('view', [
            'model' => $model,
            'fileForm' => $fileForm,
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
        $newsForm = new NewsForm();
        $fileForm = new NewsFileForm();

        if ($newsForm->load(Yii::$app->request->post()) && $fileForm->load(Yii::$app->request->post())) {
            $fileForm->setUploadedFiles();
            if ($newsForm->validate() && $fileForm->validate()) {
                try {
                    $news = $this->newsService->create($newsForm, $fileForm);

                    return $this->redirect(['view', 'id' => $news->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('create', [
            'newsForm' => $newsForm,
            'fileForm' => $fileForm,
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
        $newsForm = new NewsForm();
        $newsForm->setAttributes($news->getAttributes());

        if ($newsForm->load(Yii::$app->request->post()) && $newsForm->validate()) {
            try {
                $this->newsService->edit($news, $newsForm);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'news' => $news,
            'newsForm' => $newsForm,
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
            Yii::$app->session->setFlash('success', 'Запись успешно удалена.');
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
            Yii::$app->session->setFlash('success', 'Запись успешно восстановлена.'); // Сообщение об успехе
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
                    $this->newsService->saveFiles($model, $fileModel);
                    Yii::$app->session->setFlash('success', 'Файлы успешно загружены.');
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . Html::errorSummary($fileModel));
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
