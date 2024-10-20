<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\notification\entities\NotificationType $model */
/** @var backend\forms\NotificationTypeForm $notificationTypeForm */

$this->title = 'Обновить тип нотификации: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы нотификации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notification-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'notificationTypeForm' => $notificationTypeForm
    ]) ?>

</div>
