<?php

declare(strict_types=1);

namespace backend\forms;

use src\ticket\entities\TicketStatus;
use yii\base\Model;

class TicketStatusForm extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => TicketStatus::class]

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}