<?php

declare(strict_types=1);

namespace src\role\repositories;

use Exception;
use src\role\entities\Role;
use Yii;
use yii\rbac\Assignment;
use yii\rbac\ManagerInterface;

class RoleRepository
{
    protected ManagerInterface $authManager;

    public function __construct(ManagerInterface $authManager)
    {
        $this->authManager = $authManager;
    }

    public function findByName(string $name): Role
    {
        $authRole = $this->authManager->getRole($name);

        if (!$authRole) {
            throw new Exception("Роль {$name} не найдена.");
        }

        return Role::create($authRole->name, $authRole->description);
    }

    public function existsByName(string $name): bool
    {
        return (bool)$this->authManager->getRole($name);
    }

    /**
     * Сохранение новой роли
     */
    public function create(Role $role): bool
    {
        if ($this->authManager->getRole($role->name)) {
            throw new Exception('Роль уже существует.');
        }

        $authRole = $this->authManager->createRole($role->name);
        $authRole->description = $role->description;

        return $this->authManager->add($authRole);
    }

    public function update(Role $role): bool
    {
        $authRole = $this->authManager->getRole($role->name);

        if (!$authRole) {
            throw new Exception('Роль не найдена.');
        }

        $authRole->description = $role->description;

        return $this->authManager->update($authRole->name, $authRole);
    }

    /**
     * Удаление роли
     */
    public function remove(Role $role): bool
    {
        $role = $this->authManager->getRole($role->name);
        if (!$role) {
            throw new Exception('Роль не найдена.');
        }

        return $this->authManager->remove($role);
    }

    /**
     * Получение всех ролей
     */
    public function getAll(): array
    {
        $auth = Yii::$app->authManager;
        return $auth->getRoles();
    }

    /**
     * Назначить роль пользователю
     */
    public function assignRoleToUser(string $roleName, int $userId): Assignment
    {
        $role = $this->authManager->getRole($roleName);
        if (!$role) {
            throw new Exception('Роль не найдена.');
        }

        return $this->authManager->assign($role, $userId);
    }

    /**
     * Убрать роль у пользователя
     */
    public function revokeRoleFromUser(string $roleName, int $userId): bool
    {
        $role = $this->authManager->getRole($roleName);
        if (!$role) {
            throw new Exception('Роль не найдена.');
        }

        return $this->authManager->revoke($role, $userId);
    }

    /**
     * Получить роли пользователя
     */
    public function getUserRoles(int $userId): array
    {
        return $this->authManager->getRolesByUser($userId);
    }
}