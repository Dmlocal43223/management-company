<?php

declare(strict_types=1);

namespace backend\forms;

use src\notification\entities\NotificationType;
use yii\base\Model;

class NotificationTypeForm extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetClass' => NotificationType::class]

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}