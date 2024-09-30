<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 */
class m240928_165830_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string()->notNull()->unique(),
            'status_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'type_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'closed_at' => $this->dateTime()->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-ticket-status_id}}',
            '{{%ticket}}',
            'status_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket-status_id}}',
            '{{%ticket}}',
            'status_id',
            '{{%ticket_status}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket-type_id}}',
            '{{%ticket}}',
            'type_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket-type_id}}',
            '{{%ticket}}',
            'type_id',
            '{{%ticket_type}}',
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
            '{{%fk-ticket-type_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-type_id}}',
            '{{%ticket}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket-status_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-status_id}}',
            '{{%ticket}}'
        );

        $this->dropTable('{{%ticket}}');
    }
}
