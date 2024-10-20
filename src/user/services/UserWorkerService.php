<?php

declare(strict_types=1);

namespace src\user\services;

use src\location\entities\House;
use src\user\entities\User;
use src\user\entities\UserWorker;
use src\user\repositories\UserWorkerRepository;
use Yii;
use yii\db\Exception;

class UserWorkerService
{
    private UserWorkerRepository $userWorkerRepository;

    public function __construct(UserWorkerRepository $userWorkerRepository)
    {
        $this->userWorkerRepository = $userWorkerRepository;
    }

    public function assignToUser(User $user, House $house): void
    {
        $userWorker = $this->userWorkerRepository->findByUserAndHouse($user, $house);

        if (!$userWorker) {
            $userWorker = UserWorker::create($user, $house);
        }

        $userWorker->activate();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->userWorkerRepository->save($userWorker);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function revokeFromUser(User $user, House $house): void
    {
        $userWorker = $this->userWorkerRepository->findByUserAndHouse($user, $house);

        if (!$userWorker) {
            $userWorker = UserWorker::create($user, $house);
        }

        $userWorker->deactivate();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->userWorkerRepository->save($userWorker);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}