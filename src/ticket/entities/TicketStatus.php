<?php

declare(strict_types=1);

namespace src\ticket\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ticket_status".
 *
 * @property int $id
 * @property string $name
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TicketHistory[] $ticketHistories
 * @property Ticket[] $tickets
 */
class TicketStatus extends ActiveRecord
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

    public const STATUS_NEW_ID = 1;
    public const STATUS_PROCESSED_ID = 2;
    public const STATUS_CLOSED_ID = 3;
    public const STATUS_CANCELED_ID = 4;
    public const STATUS_DELETED_ID = 5;

    public const CLOSING_STATUS_IDS = [
        self::STATUS_CLOSED_ID,
        self::STATUS_CANCELED_ID
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%ticket_status}}';
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
            [['name'], 'required'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'deleted' => 'Удалено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(string $name): static
    {
        $ticketStatus = new static();
        $ticketStatus->name = $name;
        $ticketStatus->deleted = self::STATUS_ACTIVE;
        $ticketStatus->created_at = new Expression('CURRENT_TIMESTAMP');

        return $ticketStatus;
    }

    public function edit(string $name): void
    {
        $this->name = $name;
    }

    public function remove(): void
    {
        $this->deleted = self::STATUS_DELETED;
    }

    public function restore(): void
    {
        $this->deleted = self::STATUS_ACTIVE;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Gets query for [[TicketHistories]].
     *
     * @return ActiveQuery
     */
    public function getTicketHistories(): ActiveQuery
    {
        return $this->hasMany(TicketHistory::class, ['status_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     */
    public function getTickets(): ActiveQuery
    {
        return $this->hasMany(Ticket::class, ['status_id' => 'id']);
    }
}
