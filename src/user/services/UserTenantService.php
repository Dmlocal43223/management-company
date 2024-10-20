<?php

declare(strict_types=1);

namespace src\user\services;

use Exception;
use src\location\entities\Apartment;
use src\user\entities\User;
use src\user\entities\UserTenant;
use src\user\repositories\UserTenantRepository;
use Yii;

class UserTenantService
{
    private UserTenantRepository $userTenantRepository;

    public function __construct(UserTenantRepository $userTenantRepository)
    {
        $this->userTenantRepository = $userTenantRepository;
    }

    public function assignToUser(User $user, Apartment $apartment): void
    {
        $userTenant = $this->userTenantRepository->findByUserAndApartment($user, $apartment);

        if (!$userTenant) {
            $userTenant = UserTenant::create($user, $apartment);
        }

        $userTenant->activate();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->userTenantRepository->save($userTenant);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function revokeFromUser(User $user, Apartment $apartment): void
    {
        $userTenant = $this->userTenantRepository->findByUserAndApartment($user, $apartment);

        if (!$userTenant) {
            $userTenant = UserTenant::create($user, $apartment);
        }

        $userTenant->deactivate();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->userTenantRepository->save($userTenant);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}