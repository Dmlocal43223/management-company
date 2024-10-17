<?php

declare(strict_types=1);

namespace src\role\entities;

use yii\base\Model;

class Role extends Model
{
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