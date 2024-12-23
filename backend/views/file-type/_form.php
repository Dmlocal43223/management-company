<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\FileTypeForm $fileTypeForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="file-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($fileTypeForm, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
