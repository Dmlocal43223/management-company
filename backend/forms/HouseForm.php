<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\House;
use yii\base\Model;

class HouseForm extends Model
{
    public const CREATE_APARTMENTS_ENABLED = 1;

    public $number;
    public $region_id;
    public $locality_id;
    public $street_id;
    public $apartment_count;
    public $is_create_apartments;

    public function rules(): array
    {
        return [
            [['number', 'region_id', 'locality_id', 'street_id'], 'required'],
            [['number'], 'string', 'max' => 255],
            [['region_id', 'locality_id', 'street_id'], 'integer'],
            [['is_create_apartments'], 'boolean'],
            [['apartment_count'], 'integer', 'min' => 1],
            [['apartment_count'], 'required', 'when' => function($model) {
                return $model->is_create_apartments == self::CREATE_APARTMENTS_ENABLED;
            }, 'whenClient' => "function (attribute, value) {
            return $('#create-apartments-checkbox').is(':checked');
            }"],
            [['street_id', 'number'], 'unique', 'targetClass' => House::class, 'targetAttribute' => ['street_id', 'number'],
                'message' => 'Комбинация идентификатора местоположения и имени должна быть уникальной.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'number' => 'Номер',
            'region_id' => 'Регион',
            'locality_id' => 'Населенный пункт',
            'street_id' => 'Улица',
            'is_create_apartments' => 'Создать квартиры',
            'apartment_count' => 'Количество квартир'
        ];
    }

    public function loadFromModel(House $house): void
    {
        $this->setAttributes($house->getAttributes());
        $this->region_id = $house?->street?->locality?->region_id;
        $this->locality_id = $house?->street?->locality_id;
    }
}