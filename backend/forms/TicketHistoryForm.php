<?php

declare(strict_types=1);

namespace backend\forms;

use src\ticket\entities\Ticket;
use src\ticket\entities\TicketStatus;
use src\user\entities\User;

class TicketHistoryForm
{
    public $reason;
    public $ticket_id;
    public $status_id;
    public $created_user_id;

    public function rules(): array
    {
        return [
            [['name', 'ticket_id', 'status_id', 'created_user_id'], 'required'],
            [['reason'], 'string', 'max' => 255],
            [['ticket_id', 'status_id', 'created_user_id'], 'integer'],
            [['ticket_id'], 'exist', 'targetClass' => Ticket::class, 'targetAttribute' => 'id', 'message' => 'Заявка не найдена.'],
            [['status_id'], 'exist', 'targetClass' => TicketStatus::class, 'targetAttribute' => 'id', 'message' => 'Статус не найден.'],
            [['created_user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id', 'message' => 'Пользователь не найден.'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'reason' => 'Причина',
            'ticket_id' => 'Заявка',
            'status_id' => 'Статус',
            'created_user_id' => 'Пользователь'
        ];
    }
}