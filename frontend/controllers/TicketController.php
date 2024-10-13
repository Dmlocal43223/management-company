<?php

namespace frontend\controllers;

use backend\forms\TicketForm;
use Exception;
use frontend\forms\TicketFileForm;
use src\location\repositories\ApartmentRepository;
use src\ticket\repositories\TicketRepository;
use src\ticket\repositories\TicketTypeRepository;
use src\ticket\services\TicketService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class TicketController extends Controller
{
    private TicketTypeRepository $ticketTypeRepository;
    private ApartmentRepository $apartmentRepository;
    private TicketRepository $ticketRepository;
    private TicketService $ticketService;
    public function __construct($id, $module, $config = [])
    {
        $this->ticketTypeRepository = new TicketTypeRepository();
        $this->apartmentRepository = new ApartmentRepository();
        $this->ticketRepository = new TicketRepository();
        $this->ticketService = new TicketService($this->ticketRepository);

        parent::__construct($id, $module, $config);
    }

    public function actionCreate(): Response|string
    {
        $ticketForm = new TicketForm();
        $fileForm = new TicketFileForm();
        $types = $this->ticketTypeRepository->findActiveNameWithId();
        $apartments = $this->apartmentRepository->findActiveApartmentNumbersByUser();

        if ($ticketForm->load(Yii::$app->request->post()) && $fileForm->load(Yii::$app->request->post())) {
            $fileForm->setUploadedFiles();
            if ($ticketForm->validate() && $fileForm->validate()) {
                try {
                    $news = $this->ticketService->create($ticketForm, $fileForm);

                    return $this->redirect(['view', 'id' => $news->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('create', [
            'ticketForm' => $ticketForm,
            'fileForm' => $fileForm,
            'types' => ArrayHelper::map($types, 'id', 'name'),
            'apartments' => ArrayHelper::map($apartments, 'id', 'number'),
        ]);
    }
}