<?php

use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketStatus;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var backend\forms\search\TicketSearch $searchModel */
?>

<div style="border: 1px solid #000; padding: 20px; border-radius: 5px">
    <?php
    $form = ActiveForm::begin(['method' => 'get']);
    echo $form->field($searchModel, 'house_id')->label('Объект');
    echo $form->field($searchModel, 'created_at_range')->widget(DateRangePicker::class, [
        'pluginOptions' => [
            'locale' => [
                'format' => 'YYYY-MM-DD',
                'separator' => ' - ',
            ],
        ],
    ])->label('Дата создания');
    echo Html::submitButton('Поиск', ['class' => 'btn btn-primary']);
    ActiveForm::end();
    ?>
</div>
<br>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'house_id',
            'label' => 'Объект',
        ],
        [
            'attribute' => 'deleted_count',
            'label' => 'Удаленные',
            'format' => 'raw',
            'value' => function ($model) use ($searchModel) {
                return Html::a($model['deleted_count'], [
                    'ticket/index',
                    'TicketSearch[deleted]' => Ticket::STATUS_DELETED,
                    'TicketSearch[house_id]' => $model['house_id'],
                    'TicketSearch[created_at_range]' => $searchModel['created_at_range'],
                ], [
                    'data-pjax' => '0'
                ]);
            },
        ],
        [
            'attribute' => 'new_count',
            'label' => 'Новые',
            'format' => 'raw',
            'value' => function ($model) use ($searchModel) {
                return Html::a($model['new_count'], [
                    'ticket/index',
                    'TicketSearch[status_id]' => TicketStatus::STATUS_NEW_ID,
                    'TicketSearch[house_id]' => $model['house_id'],
                    'TicketSearch[created_at_range]' => $searchModel['created_at_range'],
                ], [
                    'data-pjax' => '0'
                ]);
            },
        ],
        [
            'attribute' => 'processed_count',
            'label' => 'В работ',
            'format' => 'raw',
            'value' => function ($model) use ($searchModel) {
                return Html::a($model['processed_count'], [
                    'ticket/index',
                    'TicketSearch[status_id]' => TicketStatus::STATUS_PROCESSED_ID,
                    'TicketSearch[house_id]' => $model['house_id'],
                    'TicketSearch[created_at_range]' => $searchModel['created_at_range'],
                ], [
                    'data-pjax' => '0'
                ]);
            },
        ],
        [
            'attribute' => 'closed_count',
            'label' => 'Закрытые',
            'format' => 'raw',
            'value' => function ($model) use ($searchModel) {
                return Html::a($model['closed_count'], [
                    'ticket/index',
                    'TicketSearch[status_id]' => TicketStatus::STATUS_CLOSED_ID,
                    'TicketSearch[house_id]' => $model['house_id'],
                    'TicketSearch[created_at_range]' => $searchModel['created_at_range'],
                ], [
                    'data-pjax' => '0'
                ]);
            },
        ],
        [
            'attribute' => 'canceled_count',
            'label' => 'Отмененные',
            'format' => 'raw',
            'value' => function ($model) use ($searchModel) {
                return Html::a($model['canceled_count'], [
                    'ticket/index',
                    'TicketSearch[status_id]' => TicketStatus::STATUS_CANCELED_ID,
                    'TicketSearch[house_id]' => $model['house_id'],
                    'TicketSearch[created_at_range]' => $searchModel['created_at_range'],
                ], [
                    'data-pjax' => '0'
                ]);
            },
        ]
    ],
]);