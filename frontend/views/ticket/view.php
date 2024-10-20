<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\ticket\entities\Ticket $model */
/* @var $historyDataProvider yii\data\ArrayDataProvider */
/* @var $documentDataProvider  yii\data\ArrayDataProvider */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">
    <div class="ticket-details">
        <h2>Детали заявки</h2>
        <ul class="ticket-info">
            <li><strong>Номер:</strong> <?= Html::encode($model->number) ?></li>
            <li><strong>Статус:</strong> <?= Html::encode($model->status->name) ?></li>
            <li><strong>Описание:</strong> <?= Html::encode($model->description) ?></li>
            <li><strong>Дом:</strong> <?= Html::encode($model->house->getAddress()) ?></li>
            <li><strong>Квартира:</strong> <?= Html::encode($model->apartment->number ?? 'Отсутствует') ?></li>
            <li><strong>Тип заявки:</strong> <?= Html::encode($model->type->name) ?></li>
            <li><strong>Дата закрытия:</strong> <?= Html::encode($model->closed_at ?? 'Отсутствует') ?></li>
            <li><strong>Дата создания:</strong> <?= Html::encode($model->created_at) ?></li>
        </ul>
    </div>

    <h2>История заявки</h2>
    <div class="ticket-history">
        <table class="table ticket-history-table">
            <thead>
            <tr>
                <th>Статус</th>
                <th>Комментарий</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($historyDataProvider->models as $history): ?>
                <tr>
                    <td><?= Html::encode($history['status']['name']) ?></td>
                    <td><?= Html::encode($history['reason']) ?></td>
                    <td><?= Yii::$app->formatter->asDate($history['created_at'], 'php:d/m/Y H:i:s') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2>Документы заявки</h2>
    <div class="ticket-documents">
        <table class="table ticket-documents-table">
            <thead>
            <tr>
                <th>Название файла</th>
                <th>Дата загрузки</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($documentDataProvider->models as $document): ?>
                <tr>
                    <td><?= Html::a($document['source'], $document['source'], ['target' => '_blank']) ?></td>
                    <td><?= Yii::$app->formatter->asDate($document['created_at'], 'php:d/m/Y H:i:s') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .ticket-details {
        margin-bottom: 30px;
    }

    .ticket-info {
        list-style: none;
        padding: 0;
    }

    .ticket-info li {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .ticket-history-table, .ticket-documents-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        background-color: #f9f9f9;
    }

    .ticket-history-table th, .ticket-documents-table th {
        background-color: #4CAF50;
        color: white;
        padding: 12px;
        text-align: left;
    }

    .ticket-history-table td, .ticket-documents-table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .ticket-history-table tbody tr:nth-child(even),
    .ticket-documents-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table a {
        color: #007bff;
        text-decoration: none;
    }

    .table a:hover {
        text-decoration: underline;
    }
</style>
