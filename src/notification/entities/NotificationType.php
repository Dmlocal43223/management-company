<?php

declare(strict_types=1);

namespace src\notification\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "notification_type".
 *
 * @property int $id
 * @property string $name
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Notification[] $notifications
 */
class NotificationType extends ActiveRecord
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

    public const TYPE_ASSIGN_TICKET_ID = 1;
    public const TYPE_UN_ASSIGN_TICKET_ID = 2;
    public const TYPE_CLOSE_TICKET_ID = 3;
    public const TYPE_CANCEL_TICKET_ID = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%notification_type}}';
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
        $ticketType = new static();
        $ticketType->name = $name;
        $ticketType->deleted = self::STATUS_ACTIVE;
        $ticketType->created_at = new Expression('CURRENT_TIMESTAMP');

        return $ticketType;
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
     * Gets query for [[Notifications]].
     *
     * @return ActiveQuery
     */
    public function getNotifications(): ActiveQuery
    {
        return $this->hasMany(Notification::class, ['type_id' => 'id']);
    }
}
