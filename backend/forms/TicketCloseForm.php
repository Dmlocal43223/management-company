<?php

declare(strict_types=1);

namespace backend\forms;

use src\ticket\entities\TicketStatus;
use yii\base\Model;

class TicketCloseForm extends Model
{
    public $status_id;
    public $comment;

    public function rules(): array
    {
        return [
            [['status_id', 'comment'], 'required'],
            [['comment'], 'string'],
            [['status_id'], 'integer'],
            [['status_id'], 'exist', 'targetClass' => TicketStatus::class, 'targetAttribute' => 'id', 'message' => 'Статус не найден.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status_id' => 'Статус',
            'comment' => 'Комментарий',
        ];
    }
}