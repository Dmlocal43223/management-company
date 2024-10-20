<?php

declare(strict_types=1);

namespace src\location\entities;

use HouseNotificationSettings;
use src\ticket\entities\Ticket;
use src\user\entities\UserTenant;
use src\user\entities\UserWorker;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "house".
 *
 * @property int $id
 * @property string $number
 * @property int $street_id
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property HouseNotificationSettings[] $houseNotificationSettings
 * @property Street $street
 * @property Ticket[] $tickets
 * @property UserTenant[] $userTenants
 * @property UserWorker[] $userWorkers
 */
class House extends ActiveRecord
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%house}}';
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
            [['number', 'street_id'], 'required'],
            [['street_id'], 'default', 'value' => null],
            [['street_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['number'], 'string', 'max' => 255],
            [['number', 'street_id'], 'unique', 'targetAttribute' => ['number', 'street_id']],
            [['street_id'], 'exist', 'skipOnError' => true, 'targetClass' => Street::class, 'targetAttribute' => ['street_id' => 'id']],
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
            'street_id' => 'Улица',
            'deleted' => 'Удалено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(string $number, int $street_id): static
    {
        $file = new static();
        $file->number = $number;
        $file->street_id = $street_id;
        $file->deleted = self::STATUS_ACTIVE;
        $file->created_at = new Expression('CURRENT_TIMESTAMP');

        return $file;
    }

    public function edit(string $number, int $street_id): void
    {
        $this->number = $number;
        $this->street_id = $street_id;
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
     * Gets query for [[HouseNotificationSettings]].
     *
     * @return ActiveQuery
     */
    public function getHouseNotificationSettings(): ActiveQuery
    {
        return $this->hasMany(HouseNotificationSettings::class, ['house_id' => 'id']);
    }

    /**
     * Gets query for [[Street]].
     *
     * @return ActiveQuery
     */
    public function getStreet(): ActiveQuery
    {
        return $this->hasOne(Street::class, ['id' => 'street_id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     */
    public function getTickets(): ActiveQuery
    {
        return $this->hasMany(Ticket::class, ['house_id' => 'id']);
    }

    /**
     * Gets query for [[UserTenants]].
     *
     * @return ActiveQuery
     */
    public function getUserTenants(): ActiveQuery
    {
        return $this->hasMany(UserTenant::class, ['apartment_id' => 'id'])
            ->viaTable('apartment', ['house_id' => 'id']);
    }

    /**
     * Gets query for [[UserWorkers]].
     *
     * @return ActiveQuery
     */
    public function getUserWorkers(): ActiveQuery
    {
        return $this->hasMany(UserWorker::class, ['house_id' => 'id']);
    }
}
