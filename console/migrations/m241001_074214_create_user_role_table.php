<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_role}}`.
 */
class m241001_074214_create_user_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_role}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-user_role-user_id}}',
            '{{%user_role}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-user_role-user_id}}',
            '{{%user_role}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-user_role-role_id}}',
            '{{%user_role}}',
            'role_id'
        );

        $this->addForeignKey(
            '{{%fk-user_role-role_id}}',
            '{{%user_role}}',
            'role_id',
            '{{%role}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-user_role-is_active}}',
            '{{%user_role}}',
            'is_active'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-user_role-is_active}}',
            '{{%user_role}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_role-role_id}}',
            '{{%user_role}}'
        );

        $this->dropIndex(
            '{{%idx-user_role-role_id}}',
            '{{%user_role}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_role-user_id}}',
            '{{%user_role}}'
        );

        $this->dropIndex(
            '{{%idx-user_role-user_id}}',
            '{{%user_role}}'
        );

        $this->dropTable('{{%user_role}}');
    }
}
