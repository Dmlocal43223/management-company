<?php

use src\ticket\entities\Ticket;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\widgets\ActiveForm $form */
/** @var backend\forms\TicketAssignForm $ticketAssignForm */
/** @var Ticket $model */
/** @var array $workers */

?>

<div class="close-ticket-form">
    <?php $form = ActiveForm::begin(['action' => ['ticket/assign', 'id' => $model->id]]); ?>

    <?= $form->field($ticketAssignForm, 'worker_id')->dropdownList($workers, ['prompt' => 'Выберите работника']) ?>

    <div class="form-group">
        <?= Html::submitButton('Назначить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>