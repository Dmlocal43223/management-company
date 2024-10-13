<?php

declare(strict_types=1);

namespace src\location\entities;

use src\ticket\entities\Ticket;
use src\user\entities\UserTenant;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery as ActiveQueryAlias;
use yii\db\ActiveRecord as ActiveRecordAlias;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "apartment".
 *
 * @property int $id
 * @property string $number
 * @property int $house_id
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property House $house
 * @property Ticket[] $tickets
 * @property UserTenant[] $userTenants
 */
class Apartment extends ActiveRecordAlias
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'apartment';
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
            [['number', 'house_id'], 'required'],
            [['house_id'], 'default', 'value' => null],
            [['house_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [['number', 'house_id'], 'unique', 'targetAttribute' => ['number', 'house_id']],
            [['house_id'], 'exist', 'skipOnError' => true, 'targetClass' => House::class, 'targetAttribute' => ['house_id' => 'id']],
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
            'house_id' => 'Объект',
            'deleted' => 'Удалено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(string $number, int $house_id): static
    {
        $file = new static();
        $file->number = $number;
        $file->house_id = $house_id;
        $file->deleted = self::STATUS_ACTIVE;
        $file->created_at = new Expression('CURRENT_TIMESTAMP');

        return $file;
    }

    public function edit(string $number, int $house_id): void
    {
        $this->number = $number;
        $this->house_id = $house_id;
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
     * Gets query for [[House]].
     *
     * @return ActiveQueryAlias
     */
    public function getHouse(): ActiveQueryAlias
    {
        return $this->hasOne(House::class, ['id' => 'house_id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQueryAlias
     */
    public function getTickets(): ActiveQueryAlias
    {
        return $this->hasMany(Ticket::class, ['apartment_id' => 'id']);
    }

    /**
     * Gets query for [[UserTenants]].
     *
     * @return ActiveQueryAlias
     */
    public function getUserTenants(): ActiveQueryAlias
    {
        return $this->hasMany(UserTenant::class, ['apartment_id' => 'id']);
    }
}
