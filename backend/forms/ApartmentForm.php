<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Apartment;
use yii\base\Model;

class ApartmentForm extends Model
{
    public $number;
    public $region_id;
    public $locality_id;
    public $street_id;
    public $house_id;

    public function rules(): array
    {
        return [
            [['number', 'region_id', 'locality_id', 'street_id', 'house_id'], 'required'],
            [['number'], 'string', 'max' => 255],
            [['region_id', 'locality_id', 'street_id', 'house_id'], 'integer'],
            [['house_id', 'number'], 'unique', 'targetClass' => Apartment::class, 'targetAttribute' => ['house_id', 'number'],
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
            'house_id' => 'Объект'
        ];
    }

    public function loadFromModel(Apartment $apartment): void
    {
        $this->setAttributes($apartment->getAttributes());
        $this->region_id = $apartment?->house?->street?->locality?->region_id;
        $this->locality_id = $apartment?->house?->street?->locality_id;
    }
}