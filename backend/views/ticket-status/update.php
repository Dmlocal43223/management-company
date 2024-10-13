<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\ticket\entities\TicketStatus $model */
/** @var backend\forms\TicketStatusForm $ticketStatusForm */

$this->title = 'Обновить статус заявки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статусы заявок', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="ticket-status-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketStatusForm' => $ticketStatusForm,
    ]) ?>

</div>
