<?php

declare(strict_types=1);

namespace src\news\repositories;

use backend\forms\search\NewsSearchForm;
use RuntimeException;
use src\news\entities\News;
use yii\db\ActiveQuery;

class NewsRepository
{
    public function findById(int $id): ?News
    {
        return News::findOne($id);
    }
    public function save(News $news): void
    {
        if (!$news->save()) {
            $errors = implode(', ', $news->getErrors());
            throw new RuntimeException("Ошибка сохранения {$errors}.");
        }
    }

    public function remove(News $news): void
    {
        $news->remove();

        if (!$news->save()) {
            throw new RuntimeException('Ошибка удаления.');
        }
    }

    public function restore(News $news): void
    {
        $news->restore();

        if (!$news->save()) {
            throw new RuntimeException('Ошибка восстановления.');
        }
    }

    public function getFilteredQuery(NewsSearchForm $searchModel): ActiveQuery
    {
        return News::find()->andFilterWhere([
            'id' => $searchModel->id,
            'author_id' => $searchModel->author_id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
            ])
            ->andFilterWhere(['ilike', 'title', $searchModel->title])
            ->andFilterWhere(['ilike', 'content', $searchModel->content]);
    }

    public function getQuery(): ActiveQuery
    {
        return News::find();
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return News::find()->where('0=1');
    }
}