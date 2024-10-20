<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_tenant}}`.
 */
class m240930_175731_create_user_tenant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_tenant}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'apartment_id' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-user_tenant-user_id}}',
            '{{%user_tenant}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-user_tenant-user_id}}',
            '{{%user_tenant}}',
            'user_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-user_tenant-apartment_id}}',
            '{{%user_tenant}}',
            'apartment_id'
        );

        $this->addForeignKey(
            '{{%fk-user_tenant-apartment_id}}',
            '{{%user_tenant}}',
            'apartment_id',
            '{{%apartment}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-user_tenant-is_active}}',
            '{{%user_tenant}}',
            'is_active'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-user_tenant-is_active}}',
            '{{%user_tenant}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_tenant-apartment_id}}',
            '{{%user_tenant}}'
        );

        $this->dropIndex(
            '{{%idx-user_tenant-apartment_id}}',
            '{{%user_tenant}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_tenant-user_id}}',
            '{{%user_tenant}}'
        );

        $this->dropIndex(
            '{{%idx-user_tenant-user_id}}',
            '{{%user_tenant}}'
        );

        $this->dropTable('{{%user_tenant}}');
    }
}
