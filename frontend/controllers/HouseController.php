<?php

namespace frontend\controllers;
use src\location\repositories\HouseRepository;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class HouseController extends Controller
{
    private HouseRepository $houseRepository;

    public function __construct($id, $module, $config = [])
    {
        $this->houseRepository = new HouseRepository();

        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->houseRepository->getQuery(),
            'pagination' => [
                'pageSize' => 25
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}