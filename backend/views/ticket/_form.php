<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\forms\TicketForm $ticketForm */
/** @var common\forms\TicketFileForm $fileForm */
/** @var array $houses */
/** @var array $types */

?>

<div class="ticket-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->controller->action->id === 'create'): ?>
        <?= $form->field($ticketForm, 'house_id')->dropDownList(!empty($houses) ? $houses : [], [
            'prompt' => 'Выберите дом',
            'id' => 'house-id',
        ]) ?>

        <?= $form->field($ticketForm, 'apartment_id')->dropDownList([], ['prompt' => 'Выберите квартиру', 'id' => 'apartment-id'])->label('Квартира (не обязательно)') ?>

        <?= $form->field($ticketForm, 'type_id')->dropDownList(!empty($types) ? $types : [], [
            'prompt' => 'Выберите тип',
            'id' => 'type-id',
        ]) ?>

        <?= $this->render('_uploadForm', ['form' => $form, 'fileForm' => $fileForm]) ?>
    <?php endif ?>

    <?= $form->field($ticketForm, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
$('#house-id').change(function() {
    var houseId = $(this).val();
    $.get('/apartment/find-apartments-by-house', { house_id: houseId }, function(data) {
        var apartmentSelect = $('#apartment-id');
        apartmentSelect.empty();
        apartmentSelect.append('<option value="">Выберите квартиру</option>');

        $.each(data, function(index, apartment) {
            apartmentSelect.append('<option value="' + apartment.id + '">' + apartment.number + '</option>');
        });
    });
});
JS;

$this->registerJs($script);
?>

