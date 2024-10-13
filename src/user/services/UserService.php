<?php

declare(strict_types=1);

namespace src\user\services;

use common\forms\UserInformationForm;
use Exception;
use frontend\forms\SignupForm;
use src\user\entities\User;
use src\user\entities\UserInformation;
use src\user\repositories\UserInformationRepository;
use src\user\repositories\UserRepository;
use Yii;

class UserService
{
    private UserRepository $userRepository;
    private UserInformationRepository $userInformationRepository;

    public function __construct(UserRepository $userRepository, UserInformationRepository $userInformationRepository)
    {
        $this->userRepository = $userRepository;
        $this->userInformationRepository = $userInformationRepository;
    }

    public function create(SignupForm $signupForm, UserInformationForm $userInformationForm): User
    {
        $user = User::create(
            $signupForm->username,
            $signupForm->email,
            $signupForm->password
        );

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->userRepository->save($user);

            $userInformation = UserInformation::create(
                $user,
                $userInformationForm->name,
                $userInformationForm->surname,
            );

            $this->userInformationRepository->save($userInformation);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $user;
    }
}