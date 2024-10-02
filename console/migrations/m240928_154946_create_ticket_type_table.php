<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_type}}`.
 */
class m240928_154946_create_ticket_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-ticket_type-deleted}}',
            '{{%ticket_type}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-ticket_type-deleted}}',
            '{{%ticket_type}}'
        );

        $this->dropTable('{{%ticket_type}}');
    }
}
