<?php

declare(strict_types=1);

namespace common\components;

use InvalidArgumentException;
use Yii;
use yii\base\Component;
use yii\rbac\DbManager;

class RoleManager extends Component
{
    private DbManager $authManager;

    public function __construct($config = [])
    {
        $this->authManager = Yii::$app->authManager;
        parent::__construct($config);
    }

    public function checkCurrentUserAccessToRoles(array $roleNames): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        foreach ($roleNames as $roleName) {
            if ($this->checkCurrentUserAccess($roleName)) {
                return true;
            }
        }
        return false;
    }

    private function checkCurrentUserAccess(string $roleName): bool
    {
        if (!$this->authManager->getRole($roleName)) {
            throw new InvalidArgumentException("Роль {$roleName} не существует.");
        }

        return $this->authManager->checkAccess(Yii::$app->user->id, $roleName);
    }
}