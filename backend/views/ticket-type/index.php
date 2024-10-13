<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\forms\search\TicketTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Типы заявок';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать тип заявки', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]); ?>


</div>
