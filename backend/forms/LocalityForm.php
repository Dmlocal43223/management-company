<?php

declare(strict_types=1);

namespace backend\forms;

use src\location\entities\Locality;
use yii\base\Model;

class LocalityForm extends Model
{
    public $name;
    public $region_id;

    public function rules(): array
    {
        return [
            [['name', 'region_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['region_id'], 'integer'],
            [['region_id', 'name'], 'unique', 'targetClass' => Locality::class, 'targetAttribute' => ['region_id', 'name'],
                'message' => 'Комбинация идентификатора местоположения и имени должна быть уникальной.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'region_id' => 'Регион',
        ];
    }
}