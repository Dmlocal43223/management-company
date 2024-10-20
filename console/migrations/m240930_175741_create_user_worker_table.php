<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_worker}}`.
 */
class m240930_175741_create_user_worker_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_worker}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'house_id' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-user_worker-user_id}}',
            '{{%user_worker}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-user_worker-user_id}}',
            '{{%user_worker}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-user_worker-house_id}}',
            '{{%user_worker}}',
            'house_id'
        );

        $this->addForeignKey(
            '{{%fk-user_worker-house_id}}',
            '{{%user_worker}}',
            'house_id',
            '{{%house}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-user_worker-is_active}}',
            '{{%user_worker}}',
            'is_active'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-user_worker-is_active}}',
            '{{%user_worker}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_worker-house_id}}',
            '{{%user_worker}}'
        );

        $this->dropIndex(
            '{{%idx-user_worker-house_id}}',
            '{{%user_worker}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_worker-user_id}}',
            '{{%user_worker}}'
        );

        $this->dropIndex(
            '{{%idx-user_worker-user_id}}',
            '{{%user_worker}}'
        );

        $this->dropTable('{{%user_worker}}');
    }
}
