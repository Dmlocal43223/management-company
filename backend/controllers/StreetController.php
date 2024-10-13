<?php

namespace backend\controllers;

use backend\forms\StreetForm;
use Exception;
use src\location\entities\Locality;
use src\location\entities\Street;
use backend\forms\search\StreetSearch;
use src\location\repositories\LocalityRepository;
use src\location\repositories\RegionRepository;
use src\location\repositories\StreetRepository;
use src\location\services\StreetService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * StreetController implements the CRUD actions for Street model.
 */
class StreetController extends Controller
{
    private StreetRepository $streetRepository;
    private LocalityRepository $localityRepository;
    private RegionRepository $regionRepository;
    private StreetService $streetService;
    public function __construct($id, $module, $config = [])
    {
        $this->streetRepository = new StreetRepository();
        $this->localityRepository = new LocalityRepository();
        $this->regionRepository = new RegionRepository();
        $this->streetService = new StreetService($this->streetRepository);

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
     * Lists all Street models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new StreetSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->streetRepository->getNoResultsQuery();
        } else {
            $query = $this->streetRepository->getFilteredQuery($searchModel);
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
     * Displays a single Street model.
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
     * Creates a new Street model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new StreetForm();
        $regions = $this->regionRepository->findActiveNamesWithId();
        $localities = $this->localityRepository->findActiveNamesWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->streetService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'streetForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
            'localities' => ArrayHelper::map($localities, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Street model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new StreetForm();
        $form->setAttributes($model->getAttributes());
        $form->region_id = $model->locality->region_id ?? null;
        $regions = $this->regionRepository->findActiveNamesWithId();
        $localities = $this->localityRepository->findActiveNamesWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->streetService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'streetForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
            'localities' => ArrayHelper::map($localities, 'id', 'name'),
        ]);
    }

    /**
     * Deletes an existing Street model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->streetService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->streetService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionFindStreets(int $locality_id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ArrayHelper::map(
            $this->streetRepository->findByLocalityId($locality_id),
            'id',
            'name'
        );
    }

    /**
     * Finds the Street model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Street the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Street
    {
        $model = $this->streetRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
