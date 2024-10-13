<?php

declare(strict_types=1);

namespace backend\forms;

use src\ticket\entities\TicketType;
use yii\base\Model;

class TicketTypeForm extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => TicketType::class]

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}