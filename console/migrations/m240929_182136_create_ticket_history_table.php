<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_history}}`.
 */
class m240929_182136_create_ticket_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_history}}', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'reason' => $this->text()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-ticket_history-ticket_id}}',
            '{{%ticket_history}}',
            'ticket_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket_history-ticket_id}}',
            '{{%ticket_history}}',
            'ticket_id',
            '{{%ticket}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket_history-status_id}}',
            '{{%ticket_history}}',
            'status_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket_history-status_id}}',
            '{{%ticket_history}}',
            'status_id',
            '{{%status}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket_history-created_user_id}}',
            '{{%ticket_history}}',
            'created_user_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket_history-created_user_id}}',
            '{{%ticket_history}}',
            'created_user_id',
            '{{%user}}',
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
            '{{%fk-ticket_history-created_user_id}}',
            '{{%ticket_history}}'
        );

        $this->dropIndex(
            '{{%idx-ticket_history-created_user_id}}',
            '{{%ticket_history}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket_history-status_id}}',
            '{{%ticket_history}}'
        );

        $this->dropIndex(
            '{{%idx-ticket_history-status_id}}',
            '{{%ticket_history}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket_history-ticket_id}}',
            '{{%ticket_history}}'
        );

        $this->dropIndex(
            '{{%idx-ticket_history-ticket_id}}',
            '{{%ticket_history}}'
        );

        $this->dropTable('{{%ticket_history}}');
    }
}
