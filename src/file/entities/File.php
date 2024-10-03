<?php

declare(strict_types=1);

namespace src\file\entities;

use src\user\entities\User;
use src\user\entities\UserInformation;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $source
 * @property int $type_id
 * @property int $created_user_id
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdUser
 * @property NewsFile[] $newsFiles
 * @property FileType $type
 * @property UserInformation[] $userInformations
 */
class File extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%file}}';
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
            [['source', 'type_id', 'created_user_id'], 'required'],
            [['type_id', 'created_user_id'], 'default', 'value' => null],
            [['type_id', 'created_user_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['source'], 'string', 'max' => 2048],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FileType::class, 'targetAttribute' => ['type_id' => 'id']],
            [['created_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'source' => 'Source',
            'type_id' => 'Type ID',
            'created_user_id' => 'Created User ID',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CreatedUser]].
     *
     * @return ActiveQuery
     */
    public function getCreatedUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_user_id']);
    }

    /**
     * Gets query for [[NewsFiles]].
     *
     * @return ActiveQuery
     */
    public function getNewsFiles(): ActiveQuery
    {
        return $this->hasMany(NewsFile::class, ['file_id' => 'id']);
    }

    /**
     * Gets query for [[Type]].
     *
     * @return ActiveQuery
     */
    public function getType(): ActiveQuery
    {
        return $this->hasOne(FileType::class, ['id' => 'type_id']);
    }

    /**
     * Gets query for [[UserInformations]].
     *
     * @return ActiveQuery
     */
    public function getUserInformations(): ActiveQuery
    {
        return $this->hasMany(UserInformation::class, ['avatar_file_id' => 'id']);
    }
}
