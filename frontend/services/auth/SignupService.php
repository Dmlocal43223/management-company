<?php

declare(strict_types=1);

namespace frontend\services\auth;

use DomainException;
use frontend\forms\SignupForm;
use RuntimeException;
use src\user\entities\User;

class SignupService
{
    public function signup(SignupForm $form): User
    {
        if (User::find()->andWhere(['username' => $form->username])->exists()) {
            throw new DomainException('Username is already exists');
        }

        if (User::find()->andWhere(['email' => $form->email])->exists()) {
            throw new DomainException('Email is already exists');
        }

        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        if (!$user->save()) {
            throw new RuntimeException('Saving error');
        }

        return $user;
    }
}