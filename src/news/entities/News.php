<?php

declare(strict_types=1);

namespace src\news\entities;

use src\file\entities\File;
use src\file\entities\FileType;
use src\file\entities\NewsFile;
use src\user\entities\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $author_id
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $author
 * @property NewsFile[] $newsFiles
 * @property File $previewFile
 */
class News extends ActiveRecord
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%news}}';
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
            [['title', 'content', 'author_id'], 'required'],
            [['content'], 'string'],
            [['author_id'], 'default', 'value' => null],
            [['author_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
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
            'content' => 'Содержание',
            'author_id' => 'Автор',
            'deleted' => 'Удалено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(string $title, string $content): static
    {
        $news = new static();
        $news->title = $title;
        $news->content = $content;
        $news->author_id = Yii::$app->user->id;
        $news->deleted = static::STATUS_ACTIVE;
        $news->created_at = new Expression('CURRENT_TIMESTAMP');

        return $news;
    }

    public function edit(string $title, string $content, bool $isDeleted): void
    {
        $this->title = $title;
        $this->content = $content;

        if ($isDeleted) {
            $this->remove();
        }
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
     * Gets query for [[Author]].
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[NewsFiles]].
     *
     * @return ActiveQuery
     */
    public function getNewsFiles(): ActiveQuery
    {
        return $this->hasMany(NewsFile::class, ['news_id' => 'id']);
    }

    public function getPreviewFile(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'file_id'])
            ->via('newsFiles')
            ->where(['type_id' => FileType::PREVIEW_TYPE_ID]);
    }

}
