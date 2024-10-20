<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\NotificationTypeForm $notificationTypeForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="notification-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($notificationTypeForm, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
