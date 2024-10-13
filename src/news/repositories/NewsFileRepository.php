<?php

declare(strict_types=1);

namespace src\news\repositories;

use src\news\entities\NewsFile;
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