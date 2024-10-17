<?php

declare(strict_types=1);

namespace src\user\services;

use common\forms\UserForm;
use common\forms\UserInformationForm;
use Exception;
use common\forms\PasswordForm;
use frontend\forms\SignupForm;
use RuntimeException;
use src\file\entities\File;
use src\file\entities\FileType;
use src\file\repositories\FileRepository;
use src\file\services\FileService;
use src\user\entities\User;
use src\user\entities\UserInformation;
use src\user\repositories\UserInformationRepository;
use src\user\repositories\UserRepository;
use Yii;
use yii\web\UploadedFile;

class UserService
{
    private UserRepository $userRepository;
    private UserInformationRepository $userInformationRepository;
    private FileRepository $fileRepository;
    private FileService $fileService;

    public function __construct(
        UserRepository $userRepository,
        UserInformationRepository $userInformationRepository,
        FileRepository $fileRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userInformationRepository = $userInformationRepository;
        $this->fileRepository = $fileRepository;
        $this->fileService = new FileService($this->fileRepository);
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

    public function edit(User $user, UserForm $useForm, UserInformationForm $userInformationForm): void
    {
        $user->edit($useForm->email);
        $userInformation = $user->userInformation;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($userInformationForm->avatar) {
                $oldAvatar = $userInformation->avatar;
                if ($oldAvatar) {
                    $oldAvatar->remove();
                    $this->fileRepository->save($oldAvatar);
                }

                $avatar = $this->saveFile($user, $userInformationForm->avatar);
                $userInformation->avatar_file_id = $avatar->id;
            }

            $userInformation->edit($userInformationForm->name, $userInformationForm->surname, $userInformationForm->telegram_id);

            $this->userRepository->save($user);
            $this->userInformationRepository->save($userInformation);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function remove(User $user): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->remove();
            $this->userRepository->save($user);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function restore(User $user): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->restore();
            $this->userRepository->save($user);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function changePassword(User $user, PasswordForm $passwordForm): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->setPassword($passwordForm->new_password);
            $user->generateAuthKey();
            $this->userRepository->save($user);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    private function saveFile(User $user, UploadedFile $file): File
    {
        $hash = $this->fileService->generateHash($file);

        if ($this->fileRepository->existsByHashAndUser($user, $hash)) {
            throw new RuntimeException("Файл {$file->baseName} уже загружен.");
        }

        return $this->fileService->create($file, $hash, FileType::AVATAR_TYPE_ID);
    }
}