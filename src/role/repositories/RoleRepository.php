<?php

declare(strict_types=1);

namespace src\role\repositories;

use Exception;
use http\Exception\RuntimeException;
use src\role\entities\Role;
use src\ticket\entities\TicketType;
use src\user\entities\User;
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

    public function getByName(string $name): Role
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

    public function remove(Role $role): bool
    {
        $role = $this->authManager->getRole($role->name);
        if (!$role) {
            throw new Exception('Роль не найдена.');
        }

        return $this->authManager->remove($role);
    }

    public function getAll(): array
    {
        $auth = Yii::$app->authManager;
        return $auth->getRoles();
    }

    public function assignRoleToUser(string $roleName, int $userId): void
    {
        $role = $this->authManager->getRole($roleName);
        if (!$role) {
            throw new Exception('Роль не найдена.');
        }

        $this->authManager->assign($role, $userId);
    }

    public function revokeRoleFromUser(string $roleName, int $userId): void
    {
        $role = $this->authManager->getRole($roleName);
        if (!$role) {
            throw new Exception('Роль не найдена.');
        }

        $this->authManager->revoke($role, $userId);
    }

    public function getUserRoles(User $user): array
    {
        return $this->authManager->getRolesByUser($user->id);
    }

    public function getRoleForTicketAssignment(TicketType $ticketType): Role
    {
        $roleName = match ($ticketType->id) {
            TicketType::TYPE_APPEAL_ID,
            TicketType::TYPE_COMPLAINT_ID => Role::MANAGER,
            TicketType::TYPE_PLUMBER_CALL_ID => Role::PLUMBER,
            TicketType::TYPE_CARPENTER_CALL_ID => Role::CARPENTER,
            TicketType::TYPE_ELECTRICIAN_CALL_ID => Role::ELECTRICIAN,
            TicketType::TYPE_CLEANER_CALL_ID  => Role::CLEANER,
            default => throw new RuntimeException("Неизвестный тип заявки {$ticketType->id}"),
        };

        return $this->getByName($roleName);
    }
}