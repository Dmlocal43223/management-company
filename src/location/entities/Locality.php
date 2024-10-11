<?php

declare(strict_types=1);

namespace src\location\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "locality".
 *
 * @property int $id
 * @property string $name
 * @property int $region_id
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Region $region
 * @property Street[] $streets
 */
class Locality extends ActiveRecord
{
    public const STATUS_ACTIVE = 0;
    public const STATUS_DELETED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%locality}}';
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
            [['name', 'region_id'], 'required'],
            [['region_id'], 'default', 'value' => null],
            [['region_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name', 'region_id'], 'unique', 'targetAttribute' => ['name', 'region_id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::class, 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'region_id' => 'Регион',
            'deleted' => 'Удалено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(string $name, int $regionId): static
    {
        $file = new static();
        $file->name = $name;
        $file->region_id = $regionId;
        $file->deleted = self::STATUS_ACTIVE;
        $file->created_at = new Expression('CURRENT_TIMESTAMP');

        return $file;
    }

    public function edit(string $name, int $regionId): void
    {
        $this->name = $name;
        $this->region_id = $regionId;
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
     * Gets query for [[Region]].
     *
     * @return ActiveQuery
     */
    public function getRegion(): ActiveQuery
    {
        return $this->hasOne(Region::class, ['id' => 'region_id']);
    }

    /**
     * Gets query for [[Streets]].
     *
     * @return ActiveQuery
     */
    public function getStreets(): ActiveQuery
    {
        return $this->hasMany(Street::class, ['locality_id' => 'id']);
    }
}
