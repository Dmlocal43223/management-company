<?php

declare(strict_types=1);

namespace backend\controllers;

use backend\forms\FileTypeForm;
use backend\forms\search\FileTypeSearch;
use Exception;
use src\file\entities\FileType;
use src\file\repositories\FileTypeRepository;
use src\file\services\FileTypeService;
use src\role\entities\Role;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * FileTypeController implements the CRUD actions for FileType model.
 */
class FileTypeController extends Controller
{
    private FileTypeRepository $fileTypeRepository;
    private FileTypeService $fileTypeService;
    public function __construct($id, $module, $config = [])
    {
        $this->fileTypeRepository = new FileTypeRepository();
        $this->fileTypeService = new FileTypeService($this->fileTypeRepository);

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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                'index',
                                'view',
                                'create',
                                'update',
                                'delete',
                                'restore',
                            ],
                            'allow' => true,
                            'roles' => [Role::ADMIN],
                        ],
                        [
                            'allow' => false,
                        ],
                    ],
                ],
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
     * Lists all FileType models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new FileTypeSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->fileTypeRepository->getNoResultsQuery();
        } else {
            $query = $this->fileTypeRepository->getFilteredQuery($searchModel);
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
     * Displays a single FileType model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FileType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new FileTypeForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->fileTypeService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'fileTypeForm' => $form,
        ]);
    }

    /**
     * Updates an existing FileType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new FileTypeForm();
        $form->setAttributes($model->getAttributes());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->fileTypeService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'fileTypeForm' => $form,
        ]);
    }

    /**
     * Deletes an existing FileType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->fileTypeService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->fileTypeService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the FileType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return FileType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): FileType
    {
        $model = $this->fileTypeRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
