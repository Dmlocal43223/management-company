<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\LocalityForm $localityForm */
/** @var yii\widgets\ActiveForm $form */
/** @var array $regions */
?>

<div class="locality-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($localityForm, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($localityForm, 'region_id')->dropDownList($regions, ['prompt' => 'Выберите регион']) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
