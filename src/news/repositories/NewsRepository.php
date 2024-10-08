<?php

declare(strict_types=1);

namespace src\news\repositories;

use RuntimeException;
use src\news\entities\News;

class NewsRepository
{
    public function save(News $news): void
    {
        if (!$news->save()) {
            throw new RuntimeException('Ошибка сохранения.');
        }
    }
}