<?php

use backend\forms\TicketCloseForm;
use src\ticket\entities\Ticket;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\widgets\ActiveForm $form */
/** @var TicketCloseForm $ticketCloseForm */
/** @var Ticket $model */
/** @var array $closingStatuses */

?>

<div class="close-ticket-form">
    <?php $form = ActiveForm::begin(['action' => ['ticket/close', 'id' => $model->id]]); ?>

    <?= $form->field($ticketCloseForm, 'status_id')->dropdownList($closingStatuses, ['prompt' => 'Выберите статус']) ?>

    <?= $form->field($ticketCloseForm, 'comment')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Закрыть', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>