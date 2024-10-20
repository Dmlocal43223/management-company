<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var src\ticket\entities\Ticket $model */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            'status_id',
            'description:ntext',
            'house_id',
            'apartment_id',
            'type_id',
            'closed_at',
            'created_at',
        ],
    ]) ?>

    <h2>История заявки</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'created_at',
                'label' => 'Дата',
                'format' => ['date', 'php:d/m/Y H:i:s'],
            ],
            [
                'attribute' => 'status_id',
                'label' => 'Статус',
                'value' => function ($model) {
                    return $model->status_id;
                },
            ],
            [
                'attribute' => 'comment',
                'label' => 'Комментарий',
            ],
        ],
    ]); ?>

    <h2>Документы заявки</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'file_name',
                'label' => 'Название файла',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->file_name, ['ticket/download', 'id' => $model->id], ['target' => '_blank']);
                },
            ],
            [
                'attribute' => 'uploaded_at',
                'label' => 'Дата загрузки',
                'format' => ['date', 'php:d/m/Y H:i:s'],
            ],
            [
                'attribute' => 'uploaded_by',
                'label' => 'Загрузил',
                'value' => function ($model) {
                    return $model->uploadedBy->name; // Предполагая, что у вас есть отношение для загрузившего пользователя
                },
            ],
        ],
    ]); ?>
</div>
