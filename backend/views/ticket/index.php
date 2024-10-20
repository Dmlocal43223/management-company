<?php

use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\forms\search\TicketSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->id, ['view', 'id' => $model->id]);
                },
            ],
            'number',
            'status_id',
//            'description:ntext',
            'house_id',
            'apartment_id',
            'type_id',
            'deleted:boolean',
            [
                'attribute' => 'closed_at',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'closed_at_range',
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'YYYY-MM-DD',
                            'separator' => ' - ',
                        ],
                    ],
                    'options' => ['class' => 'form-control', 'style' => 'width: 200px;']
                ]),
                'format' => ['date', 'php:Y-m-d'],
            ],
            [
                'attribute' => 'created_at',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_range',
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'YYYY-MM-DD',
                            'separator' => ' - ',
                        ],
                    ],
                    'options' => ['class' => 'form-control', 'style' => 'width: 200px;']
                ]),
                'format' => ['date', 'php:Y-m-d'],
            ],
            [
                'attribute' => 'updated_at',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'updated_at_range',
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'YYYY-MM-DD',
                            'separator' => ' - ',
                        ],
                    ],
                    'options' => ['class' => 'form-control', 'style' => 'width: 200px;']
                ]),
                'format' => ['date', 'php:Y-m-d'],
            ],
        ],
    ]); ?>


</div>
