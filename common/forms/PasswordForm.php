<?php

namespace common\forms;

use Yii;
use yii\base\Model;

class PasswordForm extends Model
{
    public $new_password;
    public $confirm_password;

    public function rules(): array
    {
        return [
            [['new_password', 'confirm_password'], 'required'],
            [['new_password', 'confirm_password'], 'string', 'max' => 255],
            ['new_password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'new_password' => 'Новый пароль',
            'confirm_password' => 'Подтвердите пароль',
        ];
    }
}