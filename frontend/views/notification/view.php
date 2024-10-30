<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\notification\entities\Notification $model */


$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Оповещения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="notification-view">
    <div class="notification-details">
        <h2><?= Html::encode($model->title) ?></h2>
        <p class="text-muted small"><?= yii::$app->formatter->asDatetime($model->created_at); ?></p>
        <p class="notification-info"><?= Html::encode($model->body) ?></p>
    </div>
</div>

<style>
    .notification-details {
        margin-bottom: 30px;
    }

    .notification-info {
        margin-top: 10px;
        list-style: none;
        padding: 0;
    }
</style>