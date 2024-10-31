<?php

namespace backend\controllers;

use backend\forms\LocalityForm;
use backend\forms\search\LocalitySearch;
use src\location\entities\Locality;
use src\location\repositories\HouseRepository;
use src\location\repositories\LocalityRepository;
use src\location\repositories\RegionRepository;
use src\location\repositories\StreetRepository;
use src\location\services\HouseService;
use src\location\services\LocalityService;
use src\location\services\StreetService;
use src\role\entities\Role;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * LocalityController implements the CRUD actions for Locality model.
 */
class LocalityController extends Controller
{
    private LocalityRepository $localityRepository;
    private RegionRepository $regionRepository;
    private HouseRepository $houseRepository;
    private LocalityService $localityService;
    private StreetRepository $streetRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->localityRepository = new LocalityRepository();
        $this->regionRepository = new RegionRepository();
        $this->streetRepository = new StreetRepository();
        $this->houseRepository = new HouseRepository();
        $this->localityService = new LocalityService(
            $this->localityRepository,
            $this->streetRepository,
            new StreetService($this->streetRepository, $this->houseRepository, new HouseService($this->houseRepository))
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
                            'actions' => ['find-localities'],
                            'allow' => true,
                            'roles' => ['@'],
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
     * Lists all Locality models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new LocalitySearch();
        $searchModel->load($this->request->queryParams);
        $regions = $this->regionRepository->findAll();

        if (!$searchModel->validate()) {
            $query = $this->localityRepository->getNoResultsQuery();
        } else {
            $query = $this->localityRepository->getFilteredQuery($searchModel);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
        ]);
    }

    /**
     * Displays a single Locality model.
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
     * Creates a new Locality model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate(): Response|string
    {
        $form = new LocalityForm();
        $regions = $this->regionRepository->findActiveNamesWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->localityService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'localityForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Locality model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new LocalityForm();
        $form->setAttributes($model->getAttributes());
        $regions = $this->regionRepository->findActiveNamesWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->localityService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'localityForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
        ]);
    }

    /**
     * Deletes an existing Locality model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->localityService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->localityService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionFindLocalities(int $region_id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $region = $this->regionRepository->findById($region_id);

        return ArrayHelper::map(
            $this->localityRepository->findByRegion($region, Locality::STATUS_ACTIVE),
            'id',
            'name'
        );
    }

    public function actionFindLocalitiesByRegion($region_id): Response
    {
        $region = $this->regionRepository->findById($region_id);
        return $this->asJson($this->localityRepository->findByRegion($region, Locality::STATUS_ACTIVE));
    }


    /**
     * Finds the Locality model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Locality the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Locality
    {
        $model = $this->localityRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
