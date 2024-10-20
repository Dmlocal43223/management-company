<?php

declare(strict_types=1);

namespace src\role\entities;

use yii\base\Model;

class Role extends Model
{
    public const ADMIN = 'administrator';
    public const MANAGER = 'manager';
    public const PLUMBER = 'plumber';
    public const CARPENTER = 'carpenter';
    public const ELECTRICIAN = 'electrician';
    public const CLEANER = 'cleaner';
    public const TENANT = 'tenant';


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