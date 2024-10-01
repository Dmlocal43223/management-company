<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%house_notification_settings}}`.
 */
class m241001_074951_create_house_notification_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%house_notification_settings}}', [
            'id' => $this->primaryKey(),
            'house_id' => $this->integer()->notNull(),
            'is_email' => $this->boolean()->defaultValue(false),
            'is_telegram' => $this->boolean()->defaultValue(false),
            'is_web' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-house_notification_settings-house_id}}',
            '{{%house_notification_settings}}',
            'house_id'
        );

        $this->addForeignKey(
            '{{%fk-house_notification_settings-house_id}}',
            '{{%house_notification_settings}}',
            'house_id',
            '{{%house}}',
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
            '{{%fk-house_notification_settings-house_id}}',
            '{{%house_notification_settings}}'
        );

        $this->dropIndex(
            '{{%idx-house_notification_settings-house_id}}',
            '{{%house_notification_settings}}'
        );

        $this->dropTable('{{%house_notification_settings}}');
    }
}
