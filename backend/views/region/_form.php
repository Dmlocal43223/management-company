<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\forms\RegionForm $regionForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="region-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($regionForm, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
