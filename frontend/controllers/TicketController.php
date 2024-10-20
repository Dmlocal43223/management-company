<?php

namespace frontend\controllers;

use backend\forms\search\TicketSearch;
use backend\forms\TicketForm;
use Exception;
use frontend\forms\TicketFileForm;
use src\location\repositories\ApartmentRepository;
use src\role\repositories\RoleRepository;
use src\ticket\entities\Ticket;
use src\ticket\repositories\TicketHistoryRepository;
use src\ticket\repositories\TicketRepository;
use src\ticket\repositories\TicketStatusRepository;
use src\ticket\repositories\TicketTypeRepository;
use src\ticket\services\TicketService;
use src\user\repositories\UserWorkerRepository;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TicketController extends Controller
{
    private TicketTypeRepository $ticketTypeRepository;
    private ApartmentRepository $apartmentRepository;
    private TicketRepository $ticketRepository;
    private TicketHistoryRepository $ticketHistoryRepository;
    private TicketService $ticketService;
    public function __construct($id, $module, $config = [])
    {
        $this->ticketTypeRepository = new TicketTypeRepository();
        $this->apartmentRepository = new ApartmentRepository();
        $this->ticketRepository = new TicketRepository();
        $this->ticketHistoryRepository = new TicketHistoryRepository();
        $this->ticketService = new TicketService(
            $this->ticketRepository,
            new ApartmentRepository(),
            new UserWorkerRepository(),
            new TicketTypeRepository(),
            new TicketStatusRepository(),
            new TicketHistoryRepository(),
            new RoleRepository(Yii::$app->authManager)
        );

        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $searchModel = new TicketSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->ticketRepository->getNoResultsQuery();
        } else {
            $query = $this->ticketRepository->getFilteredQueryByUser($searchModel);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->ticketHistories,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate(): Response|string
    {
        $ticketForm = new TicketForm();
        $fileForm = new TicketFileForm();
        $types = $this->ticketTypeRepository->findActiveNameWithId();
        $formattedApartmentAddresses = $this->apartmentRepository->getFormattedApartmentAddressesByUser();

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
            'apartments' => $formattedApartmentAddresses,
        ]);
    }

    protected function findModel(int $id): Ticket
    {
        $model = $this->ticketRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}