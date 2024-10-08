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
            'number' => 'Number',
            'house_id' => 'House ID',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
