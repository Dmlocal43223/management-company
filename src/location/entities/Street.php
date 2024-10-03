<?php

declare(strict_types=1);

namespace src\location\entities;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "street".
 *
 * @property int $id
 * @property string $name
 * @property int $locality_id
 * @property bool $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property House[] $houses
 * @property Locality $locality
 */
class Street extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%street}}';
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
            [['name', 'locality_id'], 'required'],
            [['locality_id'], 'default', 'value' => null],
            [['locality_id'], 'integer'],
            [['deleted'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['locality_id', 'name'], 'unique', 'targetAttribute' => ['locality_id', 'name']],
            [['locality_id'], 'exist', 'skipOnError' => true, 'targetClass' => Locality::class, 'targetAttribute' => ['locality_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'locality_id' => 'Locality ID',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Houses]].
     *
     * @return ActiveQuery
     */
    public function getHouses(): ActiveQuery
    {
        return $this->hasMany(House::class, ['street_id' => 'id']);
    }

    /**
     * Gets query for [[Locality]].
     *
     * @return ActiveQuery
     */
    public function getLocality(): ActiveQuery
    {
        return $this->hasOne(Locality::class, ['id' => 'locality_id']);
    }
}
