<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification}}`.
 */
class m240929_194207_create_notification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'body' => $this->text()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_read' => $this->boolean()->notNull()->defaultValue(false),
            'type_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-notification-user_id}}',
            '{{%notification}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-notification-user_id}}',
            '{{%notification}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-notification-type_id}}',
            '{{%notification}}',
            'type_id'
        );

        $this->addForeignKey(
            '{{%fk-notification-type_id}}',
            '{{%notification}}',
            'type_id',
            '{{%notification_type}}',
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
            '{{%fk-notification-type_id}}',
            '{{%notification}}'
        );

        $this->dropIndex(
            '{{%idx-notification-type_id}}',
            '{{%notification}}'
        );

        $this->dropForeignKey(
            '{{%fk-notification-user_id}}',
            '{{%notification}}'
        );

        $this->dropIndex(
            '{{%idx-notification-user_id}}',
            '{{%notification}}'
        );

        $this->dropTable('{{%notification}}');
    }
}
