<?php

declare(strict_types=1);

namespace common\forms;

use src\user\entities\User;
use yii\base\Model;
use yii\web\UploadedFile;

class UserInformationForm extends Model
{
    public $name;
    public $surname;
    public $telegram_id;
    public $avatar;

    public function rules(): array
    {
        return [
            [['name', 'surname'], 'required'],
            [['name', 'surname', 'telegram_id'], 'string', 'max' => 255],
            [['avatar'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'telegram_id' => 'Телеграм',
            'avatar' => 'Аватар',
        ];
    }

    public function loadFromUser(User $user): void
    {
        $this->setAttributes($user->getAttributes());

        $userInformation = $user->userInformation;
        $this->name = $userInformation->name;
        $this->surname = $userInformation->surname;
        $this->telegram_id = $userInformation->telegram_id;
    }

    public function setUploadedFile(): void
    {
        $this->avatar = UploadedFile::getInstance($this, 'avatar');

    }
}