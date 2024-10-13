<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\forms\StreetForm $streetForm */
/** @var yii\widgets\ActiveForm $form */
/** @var array $regions */
/** @var array $localities */
?>

    <div class="street-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($streetForm, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($streetForm, 'region_id')->dropDownList($regions, [
            'prompt' => 'Выберите регион',
            'id' => 'region-id',
        ]) ?>

        <?= $form->field($streetForm, 'locality_id')->dropDownList(!empty($localities) ? $localities : [], [
                'prompt' => 'Выберите населенный пункт',
                'id' => 'locality-id',
                'disabled' => 'disabled'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$script = <<< JS
var localityId = '$streetForm->locality_id';
var regionId = '$streetForm->region_id';

// Функция активации/деактивации поля
function toggleFieldState(fieldId, isEnabled) {
    if (isEnabled) {
        $(fieldId).prop('disabled', false);
    } else {
        $(fieldId).prop('disabled', true);
    }
}

// Загружаем населенные пункты
function loadLocalities(regionId) {
    $.ajax({
        url: '/locality/find-localities',
        type: 'GET',
        data: {region_id: regionId},
        success: function(data) {
            if (typeof data !== 'object') {
                console.error('Unexpected data format:', data);
            } else {
                $('#locality-id').empty().append('<option value="">Выберите населенный пункт</option>');
                $.each(data, function(key, value) {
                    $('#locality-id').append('<option value="' + key + '">' + value + '</option>');
                });
                if (localityId) {
                    $('#locality-id').val(localityId);
                }
                toggleFieldState('#locality-id', true); // Активируем поле
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Если регион уже выбран, загружаем населенные пункты и разблокируем поле
if (regionId) {
    loadLocalities(regionId);
    toggleFieldState('#locality-id', true);
}

// При изменении региона загружаем населенные пункты и разблокируем поле
$('#region-id').change(function() {
    var newRegionId = $(this).val();
    loadLocalities(newRegionId);
    $('#locality-id').val(''); // Сброс значения
    toggleFieldState('#locality-id', false); // Блокируем поле, пока не загрузятся населенные пункты
});
JS;
$this->registerJs($script);
?>