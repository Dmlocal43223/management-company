<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\TicketStatusForm $ticketStatusForm */

$this->title = 'Создать статус заявки';
$this->params['breadcrumbs'][] = ['label' => 'Статусы заявок', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketStatusForm' => $ticketStatusForm,
    ]) ?>

</div>
