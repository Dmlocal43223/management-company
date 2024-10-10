<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_information}}`.
 */
class m240930_201801_create_user_information_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_information}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'surname' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'telegram_id' => $this->string()->null(),
            'avatar_file_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            'idx-user_information-user_id',
            '{{%user_information}}',
            'user_id',
            true
        );

        $this->addForeignKey(
            'fk-user_information-user_id',
            '{{%user_information}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            'idx-user_information-avatar_file_id',
            '{{%user_information}}',
            'avatar_file_id'
        );

        $this->addForeignKey(
            'fk-user_information-avatar_file_id',
            '{{%user_information}}',
            'avatar_file_id',
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
            'fk-user_information-avatar_file_id',
            '{{%user_information}}'
        );

        $this->dropIndex(
            'idx-user_information-avatar_file_id',
            '{{%user_information}}'
        );

        $this->dropForeignKey(
            'fk-user_information-user_id',
            '{{%user_information}}'
        );

        $this->dropIndex(
            'idx-user_information-user_id',
            '{{%user_information}}'
        );

        $this->dropTable('{{%user_information}}');
    }
}
