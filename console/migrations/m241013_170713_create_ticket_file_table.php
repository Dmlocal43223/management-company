<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_file}}`.
 */
class m241013_170713_create_ticket_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_file}}', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer()->notNull(),
            'file_id'  => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-ticket_file-ticket_id}}',
            '{{%ticket_file}}',
            'ticket_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket_file-ticket_id}}',
            '{{%ticket_file}}',
            'ticket_id',
            '{{%ticket}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket_file-file_id}}',
            '{{%ticket_file}}',
            'file_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket_file-file_id}}',
            '{{%ticket_file}}',
            'file_id',
            '{{%file}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-ticket_file-file_id}}',
            '{{%ticket_file}}'
        );

        $this->dropIndex(
            '{{%idx-ticket_file-file_id}}',
            '{{%ticket_file}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket_file-ticket_id}}',
            '{{%ticket_file}}'
        );

        $this->dropIndex(
            '{{%idx-ticket_file-ticket_id}}',
            '{{%ticket_file}}'
        );

        $this->dropTable('{{%ticket_file}}');
    }
}
