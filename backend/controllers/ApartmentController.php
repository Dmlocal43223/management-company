<?php

namespace backend\controllers;

use backend\forms\ApartmentForm;
use Exception;
use src\location\entities\Apartment;
use backend\forms\search\ApartmentSearch;
use src\location\repositories\ApartmentRepository;
use src\location\repositories\HouseRepository;
use src\location\repositories\LocalityRepository;
use src\location\repositories\RegionRepository;
use src\location\repositories\StreetRepository;
use src\location\services\ApartmentService;
use src\user\repositories\UserRepository;
use src\user\repositories\UserTenantRepository;
use src\user\services\UserTenantService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ApartmentController implements the CRUD actions for Apartment model.
 */
class ApartmentController extends Controller
{
    private ApartmentRepository $apartmentRepository;
    private StreetRepository $streetRepository;
    private LocalityRepository $localityRepository;
    private RegionRepository $regionRepository;
    private HouseRepository $houseRepository;
    private UserRepository $userRepository;
    private ApartmentService $apartmentService;
    private UserTenantService $userTenantService;

    public function __construct($id, $module, $config = [])
    {
        $this->apartmentRepository = new ApartmentRepository();
        $this->streetRepository = new StreetRepository();
        $this->localityRepository = new LocalityRepository();
        $this->regionRepository = new RegionRepository();
        $this->houseRepository = new HouseRepository();
        $this->userRepository = new UserRepository();
        $this->apartmentService = new ApartmentService($this->apartmentRepository);
        $this->userTenantService = new UserTenantService(new UserTenantRepository());

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
     * Lists all Apartment models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new ApartmentSearch();
        $searchModel->load($this->request->queryParams);
        $houses = $this->houseRepository->findAll();

        if (!$searchModel->validate()) {
            $query = $this->apartmentRepository->getNoResultsQuery();
        } else {
            $query = $this->apartmentRepository->getFilteredQuery($searchModel);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'houses' => ArrayHelper::map($houses, 'id', 'number'),
        ]);
    }

    /**
     * Displays a single Apartment model.
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
     * Creates a new Apartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new ApartmentForm();
        $regions = $this->regionRepository->findActiveNamesWithId();
        $localities = $this->localityRepository->findActiveNamesWithId();
        $streets = $this->streetRepository->findActiveNamesWithId();
        $houses = $this->houseRepository->findActiveNumbersWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->apartmentService->create($form);

                return $this->redirect(['view', 'id' => $news->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'apartmentForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
            'localities' => ArrayHelper::map($localities, 'id', 'name'),
            'streets' => ArrayHelper::map($streets, 'id', 'name'),
            'houses' => ArrayHelper::map($houses, 'id', 'number'),
        ]);
    }

    /**
     * Updates an existing Apartment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new ApartmentForm();
        $form->loadFromModel($model);
        $regions = $this->regionRepository->findActiveNamesWithId();
        $localities = $this->localityRepository->findActiveNamesWithId();
        $streets = $this->streetRepository->findActiveNamesWithId();
        $houses = $this->houseRepository->findActiveNumbersWithId();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->apartmentService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'apartmentForm' => $form,
            'regions' => ArrayHelper::map($regions, 'id', 'name'),
            'localities' => ArrayHelper::map($localities, 'id', 'name'),
            'streets' => ArrayHelper::map($streets, 'id', 'name'),
            'houses' => ArrayHelper::map($houses, 'id', 'number'),
        ]);
    }

    /**
     * Deletes an existing Apartment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->apartmentService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->apartmentService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionAssign(): Response
    {
        $userId = Yii::$app->request->post('userId');
        $apartmentId = Yii::$app->request->post('apartmentId');

        try {
            $user = $this->userRepository->findById($userId);
            $apartment = $this->apartmentRepository->findById($apartmentId);
            $this->userTenantService->assignToUser($user, $apartment);
            return $this->asJson(['success' => true]);
        } catch (Exception $e) {
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actionRevoke(): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->request->post('userId');
        $apartmentId = Yii::$app->request->post('apartmentId');

        try {
            $user = $this->userRepository->findById($userId);
            $apartment = $this->apartmentRepository->findById($apartmentId);
            $this->userTenantService->revokeFromUser($user, $apartment);
            return $this->asJson(['success' => true]);
        } catch (Exception $e) {
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actionFindApartmentsByHouse(int $house_id): Response
    {
        return $this->asJson($this->apartmentRepository->findByHouseId($house_id));
    }

    /**
     * Finds the Apartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Apartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Apartment
    {
        $model = $this->apartmentRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
