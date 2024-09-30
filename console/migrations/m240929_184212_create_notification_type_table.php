<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notification_type}}`.
 */
class m240929_184212_create_notification_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notification_type}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%notification_type}}');
    }
}
