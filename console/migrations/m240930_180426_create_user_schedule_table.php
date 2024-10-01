<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_schedule}}`.
 */
class m240930_180426_create_user_schedule_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_schedule}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'work_date' => $this->date()->notNull(),
            'work_hours' => $this->json()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-user_schedule-user_id-work_date',
            '{{%user_schedule}}',
            ['user_id', 'work_date'],
            true
        );

        $this->addForeignKey(
            'fk-user_schedule-user_id',
            '{{%user_schedule}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_schedule-user_id',
            '{{%user_schedule}}'
        );

        $this->dropIndex(
            'idx-user_schedule-user_id-work_date',
            '{{%user_schedule}}'
        );

        $this->dropTable('{{%user_schedule}}');
    }
}
