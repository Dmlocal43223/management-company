<?php

declare(strict_types=1);

namespace src\file\repositories;

use src\file\entities\NewsFile;
use yii\db\Exception;

class NewsFileRepository
{
    public function save(NewsFile $newsFile): void
    {
        if (!$newsFile->save()) {
            $errors = implode(', ', $newsFile->getErrors());
            throw new Exception("Ошибка сохранения {$errors}.");
        }
    }
}