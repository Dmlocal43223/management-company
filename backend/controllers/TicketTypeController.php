<?php

namespace backend\controllers;

use backend\forms\TicketTypeForm;
use Exception;
use src\ticket\entities\TicketType;
use backend\forms\search\TicketTypeSearch;
use src\ticket\repositories\TicketTypeRepository;
use src\ticket\services\TicketTypeService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TicketTypeController implements the CRUD actions for TicketType model.
 */
class TicketTypeController extends Controller
{
    private TicketTypeRepository $ticketTypeRepository;
    private TicketTypeService $ticketTypeService;
    public function __construct($id, $module, $config = [])
    {
        $this->ticketTypeRepository = new TicketTypeRepository();
        $this->ticketTypeService = new TicketTypeService($this->ticketTypeRepository);

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
     * Lists all TicketType models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TicketTypeSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->ticketTypeRepository->getNoResultsQuery();
        } else {
            $query = $this->ticketTypeRepository->getFilteredQuery($searchModel);
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
     * Displays a single TicketType model.
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
     * Creates a new TicketType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new TicketTypeForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->ticketTypeService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'ticketTypeForm' => $form,
        ]);
    }

    /**
     * Updates an existing TicketType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new TicketTypeForm();
        $form->setAttributes($model->getAttributes());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->ticketTypeService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'ticketTypeForm' => $form,
        ]);
    }

    /**
     * Deletes an existing TicketType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->ticketTypeService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->ticketTypeService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Finds the TicketType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TicketType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): TicketType
    {
        $model = $this->ticketTypeRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
