<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Apartment;
use src\location\entities\House;
use src\ticket\entities\Ticket;
use src\ticket\entities\TicketType;
use yii\base\Model;

class TicketForm extends Model
{
    public $number;
    public $description;
    public $status_id;
    public $house_id;
    public $apartment_id;
    public $type_id;

    public function rules(): array
    {
        return [
            [['number', 'status_id', 'house_id', 'type_id'], 'required'],
            [['number'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['status_id', 'house_id', 'type_id'], 'integer'],
            [['number'], 'unique', 'targetClass' => Ticket::class, 'message' => 'Этот номер уже существует.'],
            [['house_id'], 'exist', 'targetClass' => House::class, 'targetAttribute' => 'id', 'message' => 'Объект не найден.'],
            [['apartment_id'], 'exist', 'targetClass' => Apartment::class, 'targetAttribute' => 'id', 'message' => 'Квартира не найдена.'],
            [['type_id'], 'exist', 'targetClass' => TicketType::class, 'targetAttribute' => 'id', 'message' => 'Тип не найден.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'number' => 'Номер',
            'description' => 'Описание',
            'status_id' => 'Статус',
            'house_id' => 'Объект',
            'apartment_id' => 'Квартира',
            'type_id' => 'Тип',
        ];
    }
}