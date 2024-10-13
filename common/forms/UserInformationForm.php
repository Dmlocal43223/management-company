<?php

declare(strict_types=1);

namespace common\forms;

use src\file\entities\File;
use yii\base\Model;

class UserInformationForm extends Model
{
    public $name;
    public $surname;
    public $telegram_id;
    public $avatar_file_id;

    public function rules(): array
    {
        return [
            [['name', 'surname'], 'required'],
            [['name', 'surname', 'telegram_id'], 'string', 'max' => 255],
            [['avatar_file_id'], 'integer'],
            [['avatar_file_id'], 'exist', 'targetClass' => File::class, 'targetAttribute' => 'id', 'message' => 'Аватар не найден.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'telegram_id' => 'Телеграм',
            'avatar_file_id' => 'Аватар',
        ];
    }
}