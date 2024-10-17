<?php

declare(strict_types=1);

namespace backend\forms;

use src\role\repositories\RoleRepository;
use Yii;
use yii\base\Model;
use yii\rbac\Item;

class RoleForm extends Model
{
    public $name;
    public $description;

    public function rules(): array
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            ['name', 'validateUniqueName'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
        ];
    }

    public function validateUniqueName($attribute, $params): void
    {
        if (Yii::$app->controller->action->id === 'update') {
            return;
        }

        if ((new RoleRepository(Yii::$app->authManager))->existsByName($this->name)) {
            $this->addError($attribute, 'Это имя роли уже занято.');
        }
    }
}