<?php

declare(strict_types=1);

namespace frontend\forms;

use src\user\entities\User;
use common\forms\PasswordForm as commonPasswordForm;

class PasswordForm extends commonPasswordForm
{
    public $old_password;

    public User $user;

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                [['old_password'], 'required'],
                [['old_password'], 'string', 'max' => 255],
                ['old_password', 'validatePassword'],
            ]
        );
    }

    public function attributeLabels(): array
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'old_password' => 'Старый пароль',
            ]
        );
    }

    public function validatePassword(string $attribute, array $params = null): void
    {
        if (!$this->hasErrors()) {
            if (!$this->user->validatePassword($this->old_password)) {
                $this->addError($attribute, 'Неверный пароль');
            }
        }
    }
}