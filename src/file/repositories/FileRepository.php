<?php

declare(strict_types=1);

namespace src\file\repositories;

use RuntimeException;
use src\file\entities\File;

class FileRepository
{
    public function save(File $file): void
    {
        if (!$file->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(File $file): void
    {
        $file->remove();

        if (!$file->save()) {
            throw new RuntimeException('Ошибка удаления.');
        }
    }

    public function restore(File $file): void
    {
        $file->restore();

        if (!$file->save()) {
            throw new RuntimeException('Ошибка удаления.');
        }
    }
}