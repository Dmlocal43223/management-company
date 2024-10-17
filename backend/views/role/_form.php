<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\RoleForm $roleForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->controller->action->id === 'create'): ?>
        <?= $form->field($roleForm, 'name')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?= $form->field($roleForm, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
