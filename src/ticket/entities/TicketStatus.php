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
            'name' => 'Name',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
