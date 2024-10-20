<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1 class="page-title"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
            <tr>
                <th>Номер заявки</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataProvider->models as $model): ?>
                <tr>
                    <td>
                        <div class="ticket-number">
                            <?= Html::a('<i class="fas fa-ticket-alt"></i> ' . Html::encode($model->number), ['view', 'id' => $model->id], ['class' => 'ticket-link']) ?>
                        </div>
                    </td>
                    <td>
                        <div class="ticket-status">
                            <span class="badge badge-status"><?= Html::encode($model->status->name) ?></span>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<style>
    .page-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #f9f9f9;
        margin-bottom: 20px;
    }
    .custom-table th, .custom-table td {
        padding: 15px;
        text-align: left;
    }
    .custom-table th {
        background-color: #4CAF50;
        color: white;
    }
    .custom-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .ticket-link {
        text-decoration: none;
        color: #007bff;
    }
    .ticket-link:hover {
        text-decoration: underline;
    }
    .badge-status {
        padding: 5px 10px;
        background-color: #ff9800;
        color: white;
        border-radius: 5px;
    }
    .ticket-number i {
        margin-right: 5px;
    }
</style>