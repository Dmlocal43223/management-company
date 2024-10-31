<?php

declare(strict_types=1);

namespace src\notification\entities;

use src\user\entities\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $user_id
 * @property bool $is_read
 * @property int $type_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property NotificationType $type
 * @property User $user
 */
class Notification extends ActiveRecord
{
    public const READ = 1;
    public const UN_READ = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%notification}}';
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
            [['title', 'body', 'user_id', 'type_id'], 'required'],
            [['body'], 'string'],
            [['user_id', 'type_id'], 'default', 'value' => null],
            [['user_id', 'type_id'], 'integer'],
            [['is_read'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationType::class, 'targetAttribute' => ['type_id' => 'id']],
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
            'title' => 'Заголовок',
            'body' => 'Содержание',
            'user_id' => 'Пользователь',
            'is_read' => 'Прочитано',
            'type_id' => 'Тип',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(string $title, string $body, User $user, NotificationType $type): static
    {
        $notification = new static();
        $notification->title = $title;
        $notification->body = $body;
        $notification->user_id = $user->id;
        $notification->is_read = self::UN_READ;
        $notification->type_id = $type->id;
        $notification->created_at = new Expression('CURRENT_TIMESTAMP');

        return $notification;
    }

    public function read(): void
    {
        $this->is_read = self::READ;
    }

    public function unRead(): void
    {
        $this->is_read = self::UN_READ;
    }

    public function isRead(): bool
    {
        return $this->is_read;
    }

    /**
     * Gets query for [[Type]].
     *
     * @return ActiveQuery
     */
    public function getType(): ActiveQuery
    {
        return $this->hasOne(NotificationType::class, ['id' => 'type_id']);
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
