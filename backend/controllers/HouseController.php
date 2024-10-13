<?php

namespace backend\controllers;

use backend\forms\HouseForm;
use Exception;
use src\location\entities\House;
use backend\forms\search\HouseSearch;
use src\location\repositories\HouseRepository;
use src\location\repositories\LocalityRepository;
use src\location\repositories\RegionRepository;
use src\location\repositories\StreetRepository;
use src\location\services\HouseService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HouseController implements the CRUD actions for House model.
 */
class HouseController extends Controller
{
    private StreetRepository $streetRepository;
    private LocalityRepository $localityRepository;
    private RegionRepository $regionRepository;
    private HouseRepository $houseRepository;
    private HouseService $houseService;

    public function __construct($id, $module, $config = [])
    {
        $this->streetRepository = new StreetRepository();
        $this->localityRepository = new LocalityRepository();
        $this->regionRepository = new RegionRepository();
        $this->houseRepository = new HouseRepository();
        $this->houseService = new HouseService($this->houseRepository);

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
     * Lists all House models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new HouseSearch();
        $searchModel->load($this->request->queryParams);
        $streets = $this->streetRepository->findAll();

        if (!$searchModel->validate()) {
            $query = $this->houseRepository->getNoResultsQuery();
        } else {
            $query = $this->houseRepository->getFilteredQuery($searchModel);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'streets' => ArrayHelper::map($streets, 'id', 'name'),
        ]);
    }

    /**
     * Displays a single House model.
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
     * Creates a new House model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new HouseForm();
        $regions = $this->regionRepository->findActiveNamesWithId();
        $localities = $this->localityRepository->findActiveNamesWithId();
        $streets = $this->streetRepository->findActiveNamesWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->houseService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'houseForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
            'localities' => ArrayHelper::map($localities, 'id', 'name'),
            'streets' => ArrayHelper::map($streets, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing House model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new HouseForm();
        $form->setAttributes($model->getAttributes());
        $form->region_id = $model->street->locality->region_id ?? null;
        $form->locality_id = $model->street->locality_id ?? null;
        $regions = $this->regionRepository->findActiveNamesWithId();
        $localities = $this->localityRepository->findActiveNamesWithId();
        $streets = $this->streetRepository->findActiveNamesWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->houseService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'houseForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
            'localities' => ArrayHelper::map($localities, 'id', 'name'),
            'streets' => ArrayHelper::map($streets, 'id', 'name'),
        ]);
    }

    /**
     * Deletes an existing House model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->houseService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->houseService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionFindHouses(int $street_id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ArrayHelper::map(
            $this->houseRepository->findByStreetId($street_id),
            'id',
            'number'
        );
    }

    /**
     * Finds the House model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return House the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): House
    {
        $model = $this->houseRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}