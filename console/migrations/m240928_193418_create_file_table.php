<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file}}`.
 */
class m240928_193418_create_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'source' => $this->string(2048)->notNull(),
            'type_id' => $this->integer()->notNull(),
            'hash' => $this->string(64)->notNull(),
            'size' => $this->integer()->notNull(),
            'created_user_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-file-type_id}}',
            '{{%file}}',
            'type_id'
        );

        $this->addForeignKey(
            '{{%fk-file-type_id}}',
            '{{%file}}',
            'type_id',
            '{{%file_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-file-created_user_id}}',
            '{{%file}}',
            'created_user_id'
        );

        $this->addForeignKey(
            '{{%fk-file-created_user_id}}',
            '{{%file}}',
            'created_user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-file-deleted}}',
            '{{%file}}',
            'deleted'
        );

        $this->createIndex(
            '{{%idx-file-hash}}',
            '{{%file}}',
            'hash'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-file-hash}}',
            '{{%file}}'
        );

        $this->dropIndex(
            '{{%idx-file-deleted}}',
            '{{%file}}'
        );

        $this->dropForeignKey(
            '{{%fk-file-created_user_id}}',
            '{{%file}}'
        );

        $this->dropIndex(
            '{{%idx-file-created_user_id}}',
            '{{%file}}'
        );

        $this->dropForeignKey(
            '{{%fk-file-type_id}}',
            '{{%file}}'
        );

        $this->dropIndex(
            '{{%idx-file-type_id}}',
            '{{%file}}'
        );

        $this->dropTable('{{%file}}');
    }
}
