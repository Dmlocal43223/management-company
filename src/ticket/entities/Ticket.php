<?php

declare(strict_types=1);

namespace src\ticket\entities;

use http\Exception\RuntimeException;
use src\location\entities\Apartment;
use src\location\entities\House;
use src\user\entities\User;
use Yii;
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
 * @property int $worker_id
 * @property int $house_id
 * @property int $apartment_id
 * @property int $type_id
 * @property int $created_user_id
 * @property bool $deleted
 * @property string|null $closed_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Apartment $apartment
 * @property House $house
 * @property TicketStatus $status
 * @property TicketHistory[] $ticketHistories
 * @property TicketType $type
 */
class Ticket extends ActiveRecord
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

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
            [['number', 'status_id', 'description', 'worker_id', 'house_id', 'type_id', 'created_user_id'], 'required'],
            [['status_id', 'worker_id', 'house_id', 'apartment_id', 'type_id', 'created_user_id'], 'integer'],
            [['description'], 'string'],
            [['deleted'], 'boolean'],
            [['closed_at', 'created_at', 'updated_at'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [['number'], 'unique'],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketStatus::class, 'targetAttribute' => ['status_id' => 'id']],
            [['worker_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['worker_id' => 'id']],
            [['house_id'], 'exist', 'skipOnError' => true, 'targetClass' => House::class, 'targetAttribute' => ['house_id' => 'id']],
            [['apartment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apartment::class, 'targetAttribute' => ['apartment_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketType::class, 'targetAttribute' => ['type_id' => 'id']],
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
            'number' => 'Номер',
            'status_id' => 'Статус',
            'description' => 'Описание',
            'worker_id' => 'Работник',
            'house_id' => 'Объект',
            'apartment_id' => 'Квартира',
            'type_id' => 'Тип',
            'created_user_id' => 'Пользователь',
            'deleted' => 'Удалено',
            'closed_at' => 'Дата закрытия',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(
        string $description,
        int $houseId,
        int $apartmentId,
        int $typeId
    ): static
    {
        $ticket = new static();
        $ticket->status_id = TicketStatus::STATUS_NEW_ID;
        $ticket->description = $description;
        $ticket->house_id = $houseId;
        $ticket->apartment_id = $apartmentId;
        $ticket->type_id = $typeId;
        $ticket->created_user_id = Yii::$app->user->id;
        $ticket->deleted = self::STATUS_ACTIVE;
        $ticket->created_at = new Expression('CURRENT_TIMESTAMP');

        $ticket->generateNumber();

        return $ticket;
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

    public function setWorker(User $worker): void
    {
        $this->worker_id = $worker->id;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function generateNumber(): void
    {
        $prefix = match ($this->type_id) {
            TicketType::TYPE_APPEAL_ID => 'ap',
            TicketType::TYPE_COMPLAINT_ID  => 'comp',
            TicketType::TYPE_EMPLOYEE_CALL_ID  => 'ec',
            default => throw new RuntimeException("Неизвестный тип заявки {$this->type_id}"),
        };

        $this->number = "{$prefix}_" . uniqid('', true);
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
     * Gets query for [[Apartment]].
     *
     * @return ActiveQuery
     */
    public function getApartment(): ActiveQuery
    {
        return $this->hasOne(Apartment::class, ['id' => 'apartment_id']);
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
