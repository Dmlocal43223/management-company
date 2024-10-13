<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\TicketTypeForm $ticketTypeForm */

$this->title = 'Создать тип заявки';
$this->params['breadcrumbs'][] = ['label' => 'Типы заявок', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketTypeForm' => $ticketTypeForm,
    ]) ?>

</div>
