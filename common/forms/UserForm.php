<?php

declare(strict_types=1);

namespace common\forms;

use yii\base\Model;

class UserForm extends Model
{
    public $email;

    public function rules(): array
    {
        return [
            [['email'], 'required'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Почта',
        ];
    }
}