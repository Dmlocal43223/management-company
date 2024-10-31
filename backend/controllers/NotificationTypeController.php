<?php

namespace backend\controllers;

use backend\forms\NotificationTypeForm;
use Exception;
use src\notification\entities\NotificationType;
use backend\forms\search\NotificationTypeSearch;
use src\notification\repositories\NotificationTypeRepository;
use src\notification\services\NotificationTypeService;
use src\role\entities\Role;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * NotificationTypeController implements the CRUD actions for NotificationType model.
 */
class NotificationTypeController extends Controller
{
    private NotificationTypeRepository $notificationTypeRepository;
    private NotificationTypeService $notificationTypeService;

    public function __construct($id, $module, $config = [])
    {
        $this->notificationTypeRepository = new NotificationTypeRepository();
        $this->notificationTypeService = new NotificationTypeService($this->notificationTypeRepository);

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
     * Lists all NotificationType models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new NotificationTypeSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->notificationTypeRepository->getNoResultsQuery();
        } else {
            $query = $this->notificationTypeRepository->getFilteredQuery($searchModel);
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
     * Displays a single NotificationType model.
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
     * Creates a new NotificationType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new NotificationTypeForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->notificationTypeService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'notificationTypeForm' => $form,
        ]);
    }

    /**
     * Updates an existing NotificationType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new NotificationTypeForm();
        $form->setAttributes($model->getAttributes());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->notificationTypeService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'notificationTypeForm' => $form,
        ]);
    }

    /**
     * Deletes an existing NotificationType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->notificationTypeService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->notificationTypeService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the NotificationType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return NotificationType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): NotificationType
    {
        $model = $this->notificationTypeRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
