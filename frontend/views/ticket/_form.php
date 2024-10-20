<?php

use frontend\forms\TicketFileForm;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\TicketForm $ticketForm */
/** @var TicketFileForm $fileForm */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var array $apartments */
/** @var array $types */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($ticketForm, 'apartment_id')->dropDownList(!empty($apartments) ? $apartments : [], [
        'prompt' => 'Выберите квартиру',
        'id' => 'apartment-id',
    ]) ?>

    <?= $form->field($ticketForm, 'type_id')->dropDownList(!empty($types) ? $types : [], [
        'prompt' => 'Выберите тип',
        'id' => 'type-id',
    ]) ?>

    <?= $form->field($ticketForm, 'description')->textarea(['rows' => 6]) ?>

    <?= $this->render('_uploadForm', ['form' => $form, 'fileForm' => $fileForm]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>