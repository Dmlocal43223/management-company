<?php

declare(strict_types=1);

namespace src\user\entities;

use src\location\entities\Apartment;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "user_tenant".
 *
 * @property int $id
 * @property int $user_id
 * @property int $apartment_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Apartment $apartment
 * @property User $user
 */
class UserTenant extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_tenant}}';
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
            [['user_id', 'apartment_id'], 'required'],
            [['user_id', 'apartment_id'], 'default', 'value' => null],
            [['user_id', 'apartment_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['apartment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Apartment::class, 'targetAttribute' => ['apartment_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'apartment_id' => 'apartment_id',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
