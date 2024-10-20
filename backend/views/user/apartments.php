<?php

use backend\forms\UserTenantForm;
use src\location\entities\Region;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $userTenantForm UserTenantForm */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model src\user\entities\User */
/** @var yii\data\ActiveDataProvider $dataProvider */

$form = ActiveForm::begin(); ?>

<?= $form->field($userTenantForm, 'region_id')->dropDownList(
    ArrayHelper::map(Region::find()->all(), 'id', 'name'),
    ['prompt' => 'Выберите регион', 'id' => 'region-id']
) ?>

<?= $form->field($userTenantForm, 'locality_id')->dropDownList([], ['prompt' => 'Выберите город', 'id' => 'locality-id']) ?>

<?= $form->field($userTenantForm, 'street_id')->dropDownList([], ['prompt' => 'Выберите улицу', 'id' => 'street-id']) ?>

<?= $form->field($userTenantForm, 'house_id')->dropDownList([], ['prompt' => 'Выберите дом', 'id' => 'house-id']) ?>

<?= $form->field($userTenantForm, 'apartment_id')->dropDownList([], ['prompt' => 'Выберите квартиру', 'id' => 'apartment-id']) ?>

<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

<div>
<h2>Добавленные квартиры</h2>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'number',
            'label' => 'Номер',
        ],
        [
            'attribute' => 'house.street.locality.region.name',
            'label' => 'Регион',
        ],
        [
            'attribute' => 'house.street.locality.name',
            'label' => 'Населенный пункт',
        ],
        [
            'attribute' => 'house.street.name',
            'label' => 'Улица',
        ],
        [
            'attribute' => 'house.number',
            'label' => 'Дом',
        ],
        [
            'label' => 'Действия',
            'format' => 'raw',
            'value' => function ($apartment) use ($model) {
                return Html::button('Удалить', [
                    'class' => 'btn btn-danger delete-apartment',
                    'data-apartment-id' => $apartment->id,
                    'data-user-id' => $model->id,
                    'data-confirm' => 'Вы уверены, что хотите удалить эту квартиру?',
                    'data-url' => Url::to(['/apartment/revoke']),
                ]);
            },
        ],
    ],
]); ?>
</div>

<?php
$script = <<< JS
$(document).on('mousedown', '.delete-apartment', function(e) {
    e.preventDefault();

    var button = $(this);
    var apartmentId = button.data('apartment-id');
    var userId = button.data('user-id');
    var url = button.data('url');

    console.log(apartmentId, userId);
    if (confirm(button.data('confirm'))) {
        $.ajax({
            url: url, // Используем URL из data-url
            type: 'POST',
            data: { apartmentId: apartmentId, userId: userId },
            success: function(response) {
                if (response.success) {
                    // Удаляем строку из таблицы
                    button.closest('tr').remove();
                    alert('Квартира успешно убрана.');
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function() {
                alert('Произошла ошибка при удалении квартиры.');
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
            $('#apartment-id').empty().append('<option value="">Выберите квартиру</option>');
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
            $('#apartment-id').empty().append('<option value="">Выберите квартиру</option>');
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

            $('#apartment-id').empty().append('<option value="">Выберите квартиру</option>');
        });
    });

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
