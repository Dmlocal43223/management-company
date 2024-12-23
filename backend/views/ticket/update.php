<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\ticket\entities\Ticket $model */
/** @var common\forms\TicketForm $ticketForm */

$this->title = 'Обновить заявку: ' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="ticket-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketForm' => $ticketForm
    ]) ?>

</div>
