<?php

declare(strict_types=1);

namespace src\user\entities;

use src\location\entities\House;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "user_worker".
 *
 * @property int $id
 * @property int $user_id
 * @property int $house_id
 * @property bool $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property House $house
 * @property User $user
 */
class UserWorker extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_worker}}';
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
            [['user_id', 'house_id'], 'required'],
            [['user_id', 'house_id'], 'default', 'value' => null],
            [['user_id', 'house_id'], 'integer'],
            [['is_active'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['house_id'], 'exist', 'skipOnError' => true, 'targetClass' => House::class, 'targetAttribute' => ['house_id' => 'id']],
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
            'user_id' => 'Пользователь',
            'house_id' => 'Дом',
            'is_active' => 'Активный',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
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
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
