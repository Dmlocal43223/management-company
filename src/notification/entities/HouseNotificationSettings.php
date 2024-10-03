<?php

declare(strict_types=1);

use src\location\entities\House;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "house_notification_settings".
 *
 * @property int $id
 * @property int $house_id
 * @property bool|null $is_email
 * @property bool|null $is_telegram
 * @property bool|null $is_web
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property House $house
 */
class HouseNotificationSettings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%house_notification_settings}}';
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
            [['house_id'], 'required'],
            [['house_id'], 'default', 'value' => null],
            [['house_id'], 'integer'],
            [['is_email', 'is_telegram', 'is_web'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
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
            'house_id' => 'House ID',
            'is_email' => 'Is Email',
            'is_telegram' => 'Is Telegram',
            'is_web' => 'Is Web',
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
}
