<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\notification\entities\NotificationType $model */
/** @var backend\forms\NotificationTypeForm $notificationTypeForm */

$this->title = 'Создать тип нотификации';
$this->params['breadcrumbs'][] = ['label' => 'Типы нотификации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'notificationTypeForm' => $notificationTypeForm,
    ]) ?>

</div>
