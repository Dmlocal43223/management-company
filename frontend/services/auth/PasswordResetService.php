<?php

declare(strict_types=1);

namespace frontend\services\auth;

use common\entities\User;
use DomainException;
use frontend\forms\PasswordResetRequestForm;
use frontend\forms\ResetPasswordForm;
use RuntimeException;
use Yii;
use yii\base\InvalidArgumentException;

class PasswordResetService
{
    public function request(PasswordResetRequestForm $form): void
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $form->email
        ]);

        if (!$user) {
            throw new DomainException('User is not found');
        }

        $user->requestPasswordReset();

        if (!$user->save()) {
            throw new RuntimeException('Saving error');
        }

        $sent = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new RuntimeException('Sending error');
        }
    }

    public function validateToken(string $token): void
    {
        if (empty($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank');
        }

        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            throw new InvalidArgumentException('Wrong password reset token');
        }
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword(string $token, ResetPasswordForm $form): bool
    {
        $user = User::findByPasswordResetToken($token);
        $user->setPassword($form->password);
        $user->removePasswordResetToken();
        $user->generateAuthKey();

        return $user->save(false);
    }

    public function reset(string $token, ResetPasswordForm $form): void
    {
        $user = User::findByPasswordResetToken($token);
        $user->resetPassword($form->password);

        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
}