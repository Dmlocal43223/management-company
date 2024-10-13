<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 */
class m240928_185830_create_ticket_table extends Migration
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
            'worker_id' => $this->integer()->null(),
            'house_id' => $this->integer()->notNull(),
            'apartment_id' => $this->integer()->null(),
            'type_id' => $this->integer()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
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
            '{{%idx-ticket-worker_id}}',
            '{{%ticket}}',
            'worker_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket-worker_id}}',
            '{{%ticket}}',
            'worker_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket-house_id}}',
            '{{%ticket}}',
            'house_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket-house_id}}',
            '{{%ticket}}',
            'house_id',
            '{{%house}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket-apartment_id}}',
            '{{%ticket}}',
            'apartment_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket-apartment_id}}',
            '{{%ticket}}',
            'apartment_id',
            '{{%apartment}}',
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

        $this->createIndex(
            '{{%idx-ticket-created_user_id}}',
            '{{%ticket}}',
            'created_user_id'
        );

        $this->addForeignKey(
            '{{%fk-ticket-created_user_id}}',
            '{{%ticket}}',
            'created_user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-ticket-deleted}}',
            '{{%ticket}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-ticket-deleted}}',
            '{{%ticket}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket-created_user_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-created_user_id}}',
            '{{%ticket}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket-type_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-type_id}}',
            '{{%ticket}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket-apartment_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-apartment_id}}',
            '{{%ticket}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket-house_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-house_id}}',
            '{{%ticket}}'
        );

        $this->dropForeignKey(
            '{{%fk-ticket-worker_id}}',
            '{{%ticket}}'
        );

        $this->dropIndex(
            '{{%idx-ticket-worker_id}}',
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
