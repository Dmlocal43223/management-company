<?php

declare(strict_types=1);

namespace src\user\entities;

use src\file\entities\File;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "user_information".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $telegram_id
 * @property int|null $avatar_file_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property File $avatarFile
 * @property User $user
 */
class UserInformation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_information}}';
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
            [['user_id'], 'required'],
            [['user_id', 'avatar_file_id'], 'default', 'value' => null],
            [['user_id', 'avatar_file_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['telegram_id'], 'string', 'max' => 255],
            [['avatar_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['avatar_file_id' => 'id']],
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
            'telegram_id' => 'Telegram ID',
            'avatar_file_id' => 'Avatar File ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AvatarFile]].
     *
     * @return ActiveQuery
     */
    public function getAvatarFile(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'avatar_file_id']);
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
