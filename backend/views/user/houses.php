<?php

use src\location\entities\Region;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var backend\forms\UserWorkerForm $userWorkerForm */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model src\user\entities\User */
/** @var yii\data\ActiveDataProvider $dataProvider */

$form = ActiveForm::begin(); ?>

<?= $form->field($userWorkerForm, 'region_id')->dropDownList(
    ArrayHelper::map(Region::find()->all(), 'id', 'name'),
    ['prompt' => 'Выберите регион', 'id' => 'region-id']
) ?>

<?= $form->field($userWorkerForm, 'locality_id')->dropDownList([], ['prompt' => 'Выберите город', 'id' => 'locality-id']) ?>

<?= $form->field($userWorkerForm, 'street_id')->dropDownList([], ['prompt' => 'Выберите улицу', 'id' => 'street-id']) ?>

<?= $form->field($userWorkerForm, 'house_id')->dropDownList([], ['prompt' => 'Выберите дом', 'id' => 'house-id']) ?>

<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

<div>
    <h2>Добавленные дома</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'number',
                'label' => 'Номер',
            ],
            [
                'attribute' => 'street.locality.region.name',
                'label' => 'Регион',
            ],
            [
                'attribute' => 'street.locality.name',
                'label' => 'Населенный пункт',
            ],
            [
                'attribute' => 'street.name',
                'label' => 'Улица',
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function ($house) use ($model) {
                    return Html::button('Удалить', [
                        'class' => 'btn btn-danger delete-house',
                        'data-house-id' => $house->id,
                        'data-user-id' => $model->id,
                        'data-confirm' => 'Вы уверены, что хотите удалить этот дом?',
                        'data-url' => Url::to(['/house/revoke']),
                    ]);
                },
            ],
        ],
    ]); ?>
</div>

<?php
$script = <<< JS
$(document).on('mousedown', '.delete-house', function(e) {
    e.preventDefault();

    var button = $(this);
    var houseId = button.data('house-id');
    var userId = button.data('user-id');
    var url = button.data('url');

    console.log(houseId, userId);
    if (confirm(button.data('confirm'))) {
        $.ajax({
            url: url, 
            type: 'POST',
            data: { houseId: houseId, userId: userId },
            success: function(response) {
                if (response.success) {
                    button.closest('tr').remove();
                    alert('Дом успешно удален.');
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function() {
                alert('Произошла ошибка при удалении дома.');
            }
        });
    }
});

    $('#region-id').change(function() {
        var regionId = $(this).val();
        $.get('/locality/find-localities-by-region', { region_id: regionId }, function(data) {
            var localitySelect = $('#locality-id');
            localitySelect.empty();
            localitySelect.append('<option value="">Выберите город</option>');

            $.each(data, function(index, city) {
                localitySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
            });

            $('#street-id').empty().append('<option value="">Выберите улицу</option>');
            $('#house-id').empty().append('<option value="">Выберите дом</option>');
        });
    });

    $('#locality-id').change(function() {
        var localityId = $(this).val();
        $.get('/street/find-streets-by-locality', { locality_id: localityId }, function(data) {
            var streetSelect = $('#street-id');
            streetSelect.empty();
            streetSelect.append('<option value="">Выберите улицу</option>'); 

            $.each(data, function(index, street) {
                streetSelect.append('<option value="' + street.id + '">' + street.name + '</option>');
            });

            $('#house-id').empty().append('<option value="">Выберите дом</option>');
        });
    });

    $('#street-id').change(function() {
        var streetId = $(this).val();
        $.get('/house/find-houses-by-street', { street_id: streetId }, function(data) {
            var houseSelect = $('#house-id');
            houseSelect.empty();
            houseSelect.append('<option value="">Выберите дом</option>')

            $.each(data, function(index, house) {
                houseSelect.append('<option value="' + house.id + '">' + house.number + '</option>');
            });

        });
    });
JS;

$this->registerJs($script);
?>
