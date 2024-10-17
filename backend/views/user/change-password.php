<?php

use common\forms\PasswordForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $passwordForm PasswordForm */

$this->title = 'Изменить пароль';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-change-password">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($passwordForm, 'new_password')->passwordInput()->label('Новый пароль') ?>
    <?= $form->field($passwordForm, 'confirm_password')->passwordInput()->label('Повторите новый пароль') ?>

    <div class="form-group">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
