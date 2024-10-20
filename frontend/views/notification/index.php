<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Оповещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <h1 class="page-title"><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
            <tr>
                <th>Заголовок</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($dataProvider->models as $model): ?>
                <tr>
                    <td>
                        <div class="notification-title">
                            <?= Html::a('<i class="fas fa-bell"></i> ' . Html::encode($model->title), ['view', 'id' => $model->id], ['class' => 'notification-link']) ?>
                        </div>
                    </td>
                    <td>
                        <div class="notification-status">
                            <?php if ($model->is_read): ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Прочитано
                                </span>
                            <?php else: ?>
                                <span class="badge badge-warning">
                                    <i class="fas fa-envelope"></i> Не прочитано
                                </span>
                            <?php endif; ?>
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
    .notification-link {
        text-decoration: none;
        color: #007bff;
    }
    .notification-link:hover {
        text-decoration: underline;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        font-size: 14px;
    }
    .badge-success {
        background-color: #28a745;
    }
    .badge-warning {
        background-color: #ff9800;
    }
</style>