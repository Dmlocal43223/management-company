<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\TicketTypeForm $ticketTypeForm */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="ticket-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($ticketTypeForm, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
