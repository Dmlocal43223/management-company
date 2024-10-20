<?php

declare(strict_types=1);

namespace common\forms;

use src\location\entities\Apartment;
use src\location\entities\House;
use src\ticket\entities\TicketType;
use yii\base\Model;

class TicketForm extends Model
{
    public $description;
    public $apartment_id;
    public $house_id;
    public $type_id;

    public function rules(): array
    {
        return [
            [['type_id', 'description'], 'required'],
            [['description'], 'string'],
            [['type_id', 'house_id', 'apartment_id'], 'integer'],
            [['house_id'], 'exist', 'targetClass' => House::class, 'targetAttribute' => 'id', 'message' => 'Дом не найден.'],
            [['apartment_id'], 'exist', 'targetClass' => Apartment::class, 'targetAttribute' => 'id', 'message' => 'Квартира не найдена.'],
            [['type_id'], 'exist', 'targetClass' => TicketType::class, 'targetAttribute' => 'id', 'message' => 'Тип не найден.'],
            [['apartment_id', 'house_id'], 'validateAtLeastOne'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'description' => 'Описание',
            'apartment_id' => 'Квартира',
            'house_id' => 'Дом',
            'type_id' => 'Тип',
        ];
    }

    public function validateAtLeastOne($attribute, $params)
    {
        if (empty($this->apartment_id) && empty($this->house_id)) {
            $this->addError($attribute, 'Должно быть указано либо квартира, либо дом.');
        }
    }

}