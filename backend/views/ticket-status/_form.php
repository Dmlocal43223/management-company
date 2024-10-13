<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\forms\TicketStatusForm $ticketStatusForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-status-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($ticketStatusForm, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
