<?php

declare(strict_types=1);

namespace src\role\services;

use backend\forms\RoleForm;
use Exception;
use src\role\entities\Role;
use src\role\repositories\RoleRepository;
use Yii;

class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Создание новой роли
     */
    public function create(RoleForm $form): Role
    {
        $role = Role::create($form->name, $form->description);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->roleRepository->create($role);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return $role;
    }

    public function edit(Role $role, RoleForm $form): void
    {
        $role->edit($form->description);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->roleRepository->update($role);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * Удаление роли
     */
    public function removeRole(Role $role): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->roleRepository->remove($role);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function assignRoleToUser(string $roleName, int $userId): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->roleRepository->assignRoleToUser($roleName, $userId);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    public function revokeRoleFromUser(string $roleName, int $userId): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->roleRepository->revokeRoleFromUser($roleName, $userId);
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}