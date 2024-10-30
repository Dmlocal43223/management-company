<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\ApartmentForm $apartmentForm */
/** @var yii\widgets\ActiveForm $form */
/** @var array $regions */
/** @var array $localities */
/** @var array $streets */
/** @var array $houses */
?>

<div class="apartment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($apartmentForm, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($apartmentForm, 'region_id')->dropDownList($regions, [
        'prompt' => 'Выберите регион',
        'id' => 'region-id',
    ]) ?>

    <?= $form->field($apartmentForm, 'locality_id')->dropDownList(!empty($localities) ? $localities : [], [
        'prompt' => 'Выберите населенный пункт',
        'id' => 'locality-id',
        'disabled' => 'disabled'
    ]) ?>

    <?= $form->field($apartmentForm, 'street_id')->dropDownList(!empty($streets) ? $streets : [], [
        'prompt' => 'Выберите улицу',
        'id' => 'street-id',
        'disabled' => 'disabled'
    ]) ?>

    <?= $form->field($apartmentForm, 'house_id')->dropDownList(!empty($houses) ? $houses : [], [
        'prompt' => 'Выберите объект',
        'id' => 'house-id',
        'disabled' => 'disabled'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
var localityId = '$apartmentForm->locality_id';
var regionId = '$apartmentForm->region_id';
var streetId = '$apartmentForm->street_id';
var houseId = '$apartmentForm->house_id';

function toggleFieldState(fieldId, isEnabled) {
    if (isEnabled) {
        $(fieldId).prop('disabled', false);
    } else {
        $(fieldId).prop('disabled', true);
    }
}

function loadLocalities(regionId) {
    $.ajax({
        url: '/locality/find-localities',
        type: 'GET',
        data: {region_id: regionId},
        success: function(data) {
            if (typeof data !== 'object' || $.isEmptyObject(data)) {
                $('#locality-id').empty().append('<option value="">Нет доступных населенных пунктов</option>');
            } else {
                $('#locality-id').empty().append('<option value="">Выберите населенный пункт</option>');
                $.each(data, function(key, value) {
                    $('#locality-id').append('<option value="' + key + '">' + value + '</option>');
                });
                if (localityId) {
                    $('#locality-id').val(localityId);
                }
                toggleFieldState('#locality-id', true);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function loadStreets(localityId) {
    $.ajax({
        url: '/street/find-streets',
        type: 'GET',
        data: {locality_id: localityId},
        success: function(data) {
            if (typeof data !== 'object' || $.isEmptyObject(data)) {
                $('#street-id').empty().append('<option value="">Нет доступных улиц</option>');
            } else {
                $('#street-id').empty().append('<option value="">Выберите улицу</option>');
                $.each(data, function(key, value) {
                    $('#street-id').append('<option value="' + key + '">' + value + '</option>');
                });
                if (streetId) {
                    $('#street-id').val(streetId);
                }
                toggleFieldState('#street-id', true);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function loadHouses(streetId) {
    $.ajax({
        url: '/house/find-houses',
        type: 'GET',
        data: {street_id: streetId},
        success: function(data) {
            if (typeof data !== 'object' || $.isEmptyObject(data)) {
                $('#house-id').empty().append('<option value="">Нет доступных домов</option>');
            } else {
                $('#house-id').empty().append('<option value="">Выберите дом</option>');
                $.each(data, function(key, value) {
                    $('#house-id').append('<option value="' + key + '">' + value + '</option>');
                });
                if (houseId) {
                    $('#house-id').val(houseId);
                }
                toggleFieldState('#house-id', true);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

if (regionId) {
    loadLocalities(regionId);
    toggleFieldState('#locality-id', true);
}

if (localityId) {
    loadStreets(localityId);
    toggleFieldState('#street-id', true);
}

if (streetId) {
    loadHouses(streetId);
    toggleFieldState('#house-id', true);
}

$('#region-id').change(function() {
    var newRegionId = $(this).val();
    loadLocalities(newRegionId);
    $('#locality-id').val('');
    $('#street-id').empty().append('<option value="">Выберите улицу</option>').prop('disabled', true);
    $('#house-id').empty().append('<option value="">Выберите дом</option>').prop('disabled', true);
    toggleFieldState('#locality-id', false);
    toggleFieldState('#street-id', false);
    toggleFieldState('#house-id', false);
});

$('#locality-id').change(function() {
    var newLocalityId = $(this).val();
    loadStreets(newLocalityId);
    $('#street-id').val('');
    $('#house-id').empty().append('<option value="">Выберите дом</option>').prop('disabled', true);
    toggleFieldState('#street-id', false);
    toggleFieldState('#house-id', false);
});

$('#street-id').change(function() {
    var newStreetId = $(this).val();
    loadHouses(newStreetId);
    $('#house-id').val('');
    toggleFieldState('#house-id', false);
});

JS;
$this->registerJs($script);
?>