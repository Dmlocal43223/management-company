<?php

declare(strict_types=1);

namespace src\ticket\entities;

use src\user\entities\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ticket_history".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $status_id
 * @property string $reason
 * @property int $created_user_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdUser
 * @property TicketStatus $status
 * @property Ticket $ticket
 */
class TicketHistory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%ticket_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('CURRENT_TIMESTAMP'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ticket_id', 'status_id', 'reason', 'created_user_id'], 'required'],
            [['ticket_id', 'status_id', 'created_user_id'], 'default', 'value' => null],
            [['ticket_id', 'status_id', 'created_user_id'], 'integer'],
            [['reason'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['created_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Заявка',
            'status_id' => 'Статус',
            'reason' => 'Причина',
            'created_user_id' => 'Пользователь',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(
        Ticket $ticket,
        TicketStatus $status,
        string $reason
    ): static
    {
        $ticketHistory = new static();
        $ticketHistory->ticket_id = $ticket->id;
        $ticketHistory->status_id = $status->id;
        $ticketHistory->reason = $reason;
        $ticketHistory->created_user_id = Yii::$app->user->id;
        $ticketHistory->created_at = new Expression('CURRENT_TIMESTAMP');

        return $ticketHistory;
    }

    /**
     * Gets query for [[CreatedUser]].
     *
     * @return ActiveQuery
     */
    public function getCreatedUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_user_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(TicketStatus::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }
}
