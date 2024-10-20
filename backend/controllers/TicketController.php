<?php

namespace backend\controllers;

use backend\forms\search\TicketSearch;
use backend\forms\TicketAssignForm;
use backend\forms\TicketCloseForm;
use common\forms\TicketFileForm;
use common\forms\TicketForm;
use Exception;
use src\location\repositories\ApartmentRepository;
use src\location\repositories\HouseRepository;
use src\notification\repositories\NotificationTypeRepository;
use src\role\repositories\RoleRepository;
use src\ticket\entities\Ticket;
use src\ticket\repositories\TicketHistoryRepository;
use src\ticket\repositories\TicketRepository;
use src\ticket\repositories\TicketStatusRepository;
use src\ticket\repositories\TicketTypeRepository;
use src\ticket\services\TicketService;
use src\user\repositories\UserRepository;
use src\user\repositories\UserWorkerRepository;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
{
    private TicketRepository $ticketRepository;
    private TicketStatusRepository $ticketStatusRepository;
    private UserWorkerRepository $userWorkerRepository;
    private RoleRepository $roleRepository;
    private TicketTypeRepository $ticketTypeRepository;
    private HouseRepository $houseRepository;
    private ApartmentRepository $apartmentRepository;
    private UserRepository $userRepository;
    private TicketService $ticketService;
    public function __construct($id, $module, $config = [])
    {
        $this->ticketRepository = new TicketRepository();
        $this->ticketStatusRepository = new TicketStatusRepository();
        $this->userWorkerRepository = new UserWorkerRepository();
        $this->roleRepository = new RoleRepository(Yii::$app->authManager);
        $this->ticketTypeRepository = new TicketTypeRepository();
        $this->houseRepository = new HouseRepository();
        $this->apartmentRepository = new ApartmentRepository();
        $this->userRepository = new UserRepository();
        $this->ticketService = new TicketService(
            $this->ticketRepository,
            $this->apartmentRepository,
            $this->userWorkerRepository,
            $this->ticketTypeRepository,
            $this->ticketStatusRepository,
            new TicketHistoryRepository(),
            $this->houseRepository,
            new NotificationTypeRepository(),
            $this->userRepository,
            $this->roleRepository
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
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new TicketSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->ticketRepository->getNoResultsQuery();
        } else {
            $query = $this->ticketRepository->getFilteredQuery($searchModel);
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
     * Displays a single Ticket model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);

        $fileForm = new TicketFileForm();
        $ticketCloseForm = new TicketCloseForm();
        $ticketAssignForm = new TicketAssignForm();
        $closingStatuses = $this->ticketStatusRepository->getClosingStatuses();
        $role = $this->roleRepository->getRoleForTicketAssignment($model->type);
        $workers = $this->userWorkerRepository->findWorkersByHouseAndRole($model->house, $role);
        $workers = ArrayHelper::map($workers, 'id', function($model) {
            return $model->getFullName();
        });

        $historyDataProvider = new ArrayDataProvider([
            'allModels' => $model->ticketHistories,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $fileDataProvider = new ArrayDataProvider([
            'allModels' => $model->files,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'fileForm' => $fileForm,
            'ticketCloseForm' => $ticketCloseForm,
            'ticketAssignForm' => $ticketAssignForm,
            'historyDataProvider' => $historyDataProvider,
            'fileDataProvider' => $fileDataProvider,
            'closingStatuses' => ArrayHelper::map($closingStatuses, 'id', 'name'),
            'workers' => $workers,
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $ticketForm = new TicketForm();
        $fileForm = new TicketFileForm();
        $types = $this->ticketTypeRepository->findActiveNameWithId();
        $user = $this->userRepository->findById(Yii::$app->user->id);
        $formattedHouseAddresses = $this->houseRepository->getFormattedApartmentAddressesByUser($user);

        if ($ticketForm->load(Yii::$app->request->post()) && $fileForm->load(Yii::$app->request->post())) {
            $fileForm->setUploadedFiles();
            if ($ticketForm->validate() && $fileForm->validate()) {
                try {
                    $ticket = $this->ticketService->create($ticketForm, $fileForm);
                    Yii::$app->session->setFlash('success', "Заявка {$ticket->id} создана.");
                    return $this->redirect(['view', 'id' => $ticket->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('create', [
            'ticketForm' => $ticketForm,
            'fileForm' => $fileForm,
            'types' => ArrayHelper::map($types, 'id', 'name'),
            'houses' => $formattedHouseAddresses,
        ]);
    }

    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new TicketForm();
        $form->setAttributes($model->getAttributes());

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->ticketService->edit($model, $form);

                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'ticketForm' => $form,
        ]);
    }

    /**
     * Deletes an existing Ticket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->ticketService->remove($model);
            Yii::$app->session->setFlash('success', 'Заявка успешно удалена.');
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
            $this->ticketService->restore($model);
            Yii::$app->session->setFlash('success', 'Заявка успешно восстановлена.');
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionUpload(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new TicketFileForm();

        if ($form->load(Yii::$app->request->post())) {
            $form->setUploadedFiles();
            if ($form->validate()) {
                try {
                    $this->ticketService->saveFiles($model, $form);
                    Yii::$app->session->setFlash('success', 'Файлы успешно загружены.');
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . Html::errorSummary($form));
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionAssign(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new TicketAssignForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->ticketService->assign($model, $form);
                Yii::$app->session->setFlash('success', 'Работник успешно назначен.');
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . Html::errorSummary($form));
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionClose(int $id): Response|string
    {
        $model = $this->findModel($id);
        $form = new TicketCloseForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->ticketService->close($model, $form);
                Yii::$app->session->setFlash('success', 'Заявка успешно закрыта.');
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка валидации: ' . Html::errorSummary($form));
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Ticket
    {
        $model = $this->ticketRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
