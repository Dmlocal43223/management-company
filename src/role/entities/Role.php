<?php

declare(strict_types=1);

namespace src\role\entities;

use yii\base\Model;

class Role extends Model
{
    public const ADMIN = 'Администратор';
    public const MANAGER = 'Менеджер';
    public const PLUMBER = 'Сантехник';
    public const CARPENTER = 'Плотник';
    public const ELECTRICIAN = 'Электрик';
    public const CLEANER = 'Уборщик';
    public const TENANT = 'Житель';

    public const HEAD_ROLES = [
        self::ADMIN,
        self::MANAGER
    ];

    public const WORKER_ROLES = [
        self::PLUMBER,
        self::CARPENTER,
        self::ELECTRICIAN,
        self::CLEANER
    ];

    public string $name;
    public string $description;

    public static function create(string $name, string $description): static
    {
        $role = new static();
        $role->name = $name;
        $role->description = $description;

        return $role;
    }

    public function edit(string $description): void
    {
        $this->description = $description;
    }
}