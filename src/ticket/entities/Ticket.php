<?php

declare(strict_types=1);

namespace src\ticket\entities;

use src\location\entities\House;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string $number
 * @property int $status_id
 * @property string $description
 * @property int $house_id
 * @property int $type_id
 * @property bool $deleted
 * @property string|null $closed_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property House $house
 * @property TicketStatus $status
 * @property TicketHistory[] $ticketHistories
 * @property TicketType $type
 */
class Ticket extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%ticket}}';
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
            [['number', 'status_id', 'description', 'house_id', 'type_id'], 'required'],
            [['status_id', 'house_id', 'type_id'], 'default', 'value' => null],
            [['status_id', 'house_id', 'type_id'], 'integer'],
            [['description'], 'string'],
            [['deleted'], 'boolean'],
            [['closed_at', 'created_at', 'updated_at'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['house_id'], 'exist', 'skipOnError' => true, 'targetClass' => House::class, 'targetAttribute' => ['house_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketType::class, 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'status_id' => 'Status ID',
            'description' => 'Description',
            'house_id' => 'House ID',
            'type_id' => 'Type ID',
            'deleted' => 'Deleted',
            'closed_at' => 'Closed At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[House]].
     *
     * @return ActiveQuery
     */
    public function getHouse(): ActiveQuery
    {
        return $this->hasOne(House::class, ['id' => 'house_id']);
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
     * Gets query for [[TicketHistories]].
     *
     * @return ActiveQuery
     */
    public function getTicketHistories(): ActiveQuery
    {
        return $this->hasMany(TicketHistory::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return ActiveQuery
     */
    public function getType(): ActiveQuery
    {
        return $this->hasOne(TicketType::class, ['id' => 'type_id']);
    }
}
