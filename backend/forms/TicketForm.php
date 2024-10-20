<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Apartment;
use src\ticket\entities\TicketType;
use yii\base\Model;

class TicketForm extends Model
{
    public $description;
    public $apartment_id;
    public $type_id;

    public function rules(): array
    {
        return [
            [['apartment_id', 'type_id', 'description'], 'required'],
            [['description'], 'string'],
            [['type_id'], 'integer'],
            [['apartment_id'], 'exist', 'targetClass' => Apartment::class, 'targetAttribute' => 'id', 'message' => 'Квартира не найдена.'],
            [['type_id'], 'exist', 'targetClass' => TicketType::class, 'targetAttribute' => 'id', 'message' => 'Тип не найден.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'description' => 'Описание',
            'apartment_id' => 'Квартира',
            'type_id' => 'Тип',
        ];
    }
}