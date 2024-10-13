<?php

namespace src\ticket\entities;

use src\file\entities\File;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ticket_file".
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $file_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property File $file
 * @property Ticket $ticket
 */
class TicketFile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ticket_file';
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
            [['ticket_id', 'file_id'], 'required'],
            [['ticket_id', 'file_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['file_id' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Заявка',
            'file_id' => 'Файл',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function create(Ticket $ticket, File $file): static
    {
        $ticketFile = new static();
        $ticketFile->ticket_id = $ticket->id;
        $ticketFile->file_id = $file->id;

        return $ticketFile;
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
     * Gets query for [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
    }
}
