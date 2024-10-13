<?php

namespace backend\controllers;

use backend\forms\TicketStatusForm;
use Exception;
use src\ticket\entities\TicketStatus;
use backend\forms\search\TicketStatusSearch;
use src\ticket\repositories\TicketStatusRepository;
use src\ticket\services\TicketStatusService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TicketStatusController implements the CRUD actions for TicketStatus model.
 */
class TicketStatusController extends Controller
{
    private TicketStatusRepository $ticketStatusRepository;
    private TicketStatusService $ticketStatusService;
    public function __construct($id, $module, $config = [])
    {
        $this->ticketStatusRepository = new TicketStatusRepository();
        $this->ticketStatusService = new TicketStatusService($this->ticketStatusRepository);

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
     * Lists all TicketStatus models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TicketStatusSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->ticketStatusRepository->getNoResultsQuery();
        } else {
            $query = $this->ticketStatusRepository->getFilteredQuery($searchModel);
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
     * Displays a single TicketStatus model.
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
     * Creates a new TicketStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new TicketStatusForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->ticketStatusService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'ticketStatusForm' => $form,
        ]);
    }

    /**
     * Updates an existing TicketStatus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new TicketStatusForm();
        $form->setAttributes($model->getAttributes());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->ticketStatusService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'ticketStatusForm' => $form,
        ]);
    }

    /**
     * Deletes an existing TicketStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->ticketStatusService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->ticketStatusService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the TicketStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TicketStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): TicketStatus
    {
        $model = $this->ticketStatusRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
