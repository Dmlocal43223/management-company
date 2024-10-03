<?php

declare(strict_types=1);

namespace src\file\entities;

use src\news\entities\News;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "news_file".
 *
 * @property int $id
 * @property int $news_id
 * @property int $file_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property File $file
 * @property News $news
 */
class NewsFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%news_file}}';
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
            [['news_id', 'file_id'], 'required'],
            [['news_id', 'file_id'], 'default', 'value' => null],
            [['news_id', 'file_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::class, 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'news_id' => 'News ID',
            'file_id' => 'File ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[File]].
     *
     * @return ActiveQuery
     */
    public function getFile(): ActiveQuery
    {
        return $this->hasOne(File::class, ['id' => 'file_id']);
    }

    /**
     * Gets query for [[News]].
     *
     * @return ActiveQuery
     */
    public function getNews(): ActiveQuery
    {
        return $this->hasOne(News::class, ['id' => 'news_id']);
    }
}
