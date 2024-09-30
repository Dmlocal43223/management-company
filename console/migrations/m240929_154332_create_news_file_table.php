<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news_file}}`.
 */
class m240929_154332_create_news_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news_file}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news_file}}');
    }
}
