<?php

declare(strict_types=1);

namespace src\file\repositories;

use backend\forms\search\FileTypeSearch;
use src\file\entities\FileType;
use yii\db\ActiveQuery;
use yii\db\Exception;

class FileTypeRepository
{
    public function findById(int $id): ?FileType
    {
        return FileType::findOne($id);
    }

    public function save(FileType $fileType): void
    {
        if (!$fileType->save()) {
            throw new Exception('Ошибка сохранения.');
        }
    }

    public function getFilteredQuery(FileTypeSearch $searchModel): ActiveQuery
    {
        return FileType::find()->andFilterWhere([
            'id' => $searchModel->id,
            'deleted' => $searchModel->deleted,
            'created_at' => $searchModel->created_at,
            'updated_at' => $searchModel->updated_at
            ])
            ->andFilterWhere(['ilike', 'name', $searchModel->name]);
    }

    public function getNoResultsQuery(): ActiveQuery
    {
        return FileType::find()->where('0=1');
    }
}