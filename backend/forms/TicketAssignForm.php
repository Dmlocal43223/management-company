<?php

declare(strict_types=1);

namespace backend\forms;

use src\user\entities\UserWorker;
use yii\base\Model;

class TicketAssignForm extends Model
{
    public $worker_id;

    public function rules(): array
    {
        return [
            [['worker_id'], 'required'],
            [['worker_id'], 'integer'],
            [['worker_id'], 'exist', 'targetClass' => UserWorker::class, 'targetAttribute' => 'user_id', 'filter' => ['is_active' => 1], 'message' => 'Работник не найден.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'worker_id' => 'Работник',
        ];
    }
}