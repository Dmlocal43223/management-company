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
 * @property string $name
 * @property string $surname
 * @property int $user_id
 * @property string|null $telegram_id
 * @property int|null $avatar_file_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property File $avatar
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
            [['name', 'surname', 'telegram_id'], 'string', 'max' => 255],
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
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'user_id' => 'Пользователь',
            'telegram_id' => 'Телеграм',
            'avatar_file_id' => 'Аватар',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(
        User $user,
        string $name,
        string $surname,
    ): static
    {
        $userInformation = new static();
        $userInformation->user_id = $user->id;
        $userInformation->name = $name;
        $userInformation->surname = $surname;
        $userInformation->created_at = new Expression('CURRENT_TIMESTAMP');

        return $userInformation;
    }

    public function edit(
        string $name,
        string $surname,
        ?string $telegramId = null,
        ?File $avatar = null
    ): void
    {
        $this->name = $name;
        $this->surname = $surname;

        if ($telegramId) {
            $this->telegram_id = $telegramId;
        }

        if ($avatar) {
            $this->avatar_file_id = $avatar->id;
        }
    }

    /**
     * Gets query for [[AvatarFile]].
     *
     * @return ActiveQuery
     */
    public function getAvatar(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'avatar_file_id'])->andOnCondition(['deleted' => File::STATUS_ACTIVE]);
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
