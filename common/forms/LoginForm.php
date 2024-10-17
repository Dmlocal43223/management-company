<?php

namespace common\forms;

use src\user\entities\User;
use src\user\repositories\UserRepository;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['username', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'username', 'message' => 'Пользователь с таким именем не найден.'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array|null $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, array $params = null): void
    {
        if (!$this->hasErrors()) {
            $user = (new UserRepository())->findByUsername($this->username);

            if (!$user->isActive()) {
                Yii::$app->session->setFlash('error', 'Пользователь удален.');
            }

            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный пароль.');
            }
        }
    }
}
