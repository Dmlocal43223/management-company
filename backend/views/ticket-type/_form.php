<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\forms\TicketTypeForm $ticketTypeForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($ticketTypeForm, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
