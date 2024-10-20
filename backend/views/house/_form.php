<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\forms\HouseForm $houseForm */
/** @var yii\widgets\ActiveForm $form */
/** @var array $regions */
/** @var array $localities */
/** @var array $streets */
?>

<div class="house-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($houseForm, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($houseForm, 'region_id')->dropDownList($regions, [
        'prompt' => 'Выберите регион',
        'id' => 'region-id',
    ]) ?>

    <?= $form->field($houseForm, 'locality_id')->dropDownList(!empty($localities) ? $localities : [], [
        'prompt' => 'Выберите населенный пункт',
        'id' => 'locality-id',
        'disabled' => 'disabled'
    ]) ?>

    <?= $form->field($houseForm, 'street_id')->dropDownList(!empty($streets) ? $streets : [], [
        'prompt' => 'Выберите улицу',
        'id' => 'street-id',
        'disabled' => 'disabled'
    ]) ?>

    <?php if (Yii::$app->controller->action->id === 'create'): ?>
        <?= $form->field($houseForm, 'is_create_apartments')->checkbox(['id' => 'create-apartments-checkbox']) ?>

        <div id="apartment-count-field" style="display:none;">
            <?= $form->field($houseForm, 'apartment_count')->textInput(['maxlength' => true])->label('Количество квартир') ?>
        </div>    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
var localityId = '$houseForm->locality_id';
var regionId = '$houseForm->region_id';
var streetId = '$houseForm->street_id';

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
            if (typeof data !== 'object') {
                console.error('Unexpected data format:', data);
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

$('#create-apartments-checkbox').change(function() {
    if ($(this).is(':checked')) {
        $('#apartment-count-field').show();
    } else {
        $('#apartment-count-field').hide();
    }
});

if ($('#create-apartments-checkbox').is(':checked')) {
    $('#apartment-count-field').show();
}

if (regionId) {
    loadLocalities(regionId);
    toggleFieldState('#locality-id', true);
}

if (localityId) {
    loadStreets(localityId);
    toggleFieldState('#street-id', true);
}

$('#region-id').change(function() {
    var newRegionId = $(this).val();
    loadLocalities(newRegionId);
    $('#locality-id').val('');
    $('#street-id').empty().append('<option value="">Выберите улицу</option>').prop('disabled', true);
    toggleFieldState('#street-id', false);
});

$('#locality-id').change(function() {
    var newLocalityId = $(this).val();
    loadStreets(newLocalityId);
    $('#street-id').val('');
    toggleFieldState('#street-id', false);
});
JS;
$this->registerJs($script);
?>