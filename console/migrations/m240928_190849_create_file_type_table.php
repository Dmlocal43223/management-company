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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file_type}}');
    }
}
