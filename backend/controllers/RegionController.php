<?php

namespace backend\controllers;

use backend\forms\RegionForm;
use backend\forms\search\RegionSearch;
use Exception;
use src\location\entities\Locality;
use src\location\entities\Region;
use src\location\repositories\HouseRepository;
use src\location\repositories\LocalityRepository;
use src\location\repositories\RegionRepository;
use src\location\repositories\StreetRepository;
use src\location\services\HouseService;
use src\location\services\LocalityService;
use src\location\services\RegionService;
use src\location\services\StreetService;
use src\role\entities\Role;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends Controller
{
    private RegionRepository $regionRepository;
    private LocalityRepository $localityRepository;
    private StreetRepository $streetRepository;
    private HouseRepository $houseRepository;
    private RegionService $regionService;

    public function __construct($id, $module, $config = [])
    {
        $this->regionRepository = new RegionRepository();
        $this->localityRepository = new LocalityRepository();
        $this->streetRepository = new StreetRepository();
        $this->houseRepository = new HouseRepository();
        $this->regionService = new RegionService(
            $this->regionRepository,
            $this->localityRepository,
            new LocalityService(
                $this->localityRepository,
                $this->streetRepository,
                new StreetService($this->streetRepository, $this->houseRepository, new HouseService($this->houseRepository))
            )
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
                            'roles' => [Role::ADMIN, Role::MANAGER],
                        ],
                        [
                            'actions' => ['get-localities'],
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
     * Lists all Region models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new RegionSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->regionRepository->getNoResultsQuery();
        } else {
            $query = $this->regionRepository->getFilteredQuery($searchModel);
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
     * Displays a single Region model.
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
     * Creates a new Region model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new RegionForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->regionService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'regionForm' => $form,
        ]);
    }

    /**
     * Updates an existing Region model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new RegionForm();
        $form->setAttributes($model->getAttributes());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->regionService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'regionForm' => $form,
        ]);
    }

    /**
     * Deletes an existing Region model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->regionService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->regionService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionGetLocalities()
    {
        $regionId = Yii::$app->request->get('region_id');
        $cities = Locality::find()->where(['region_id' => $regionId])->all();

        return $this->asJson(ArrayHelper::map($cities, 'id', 'name'));
    }

    /**
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Region the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Region
    {
        $model = $this->regionRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
