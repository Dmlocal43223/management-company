<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file_type}}`.
 */
class m240928_190849_create_file_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-file_type-deleted}}',
            '{{%file_type}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-file_type-deleted}}',
            '{{%file_type}}'
        );

        $this->dropTable('{{%file_type}}');
    }
}
