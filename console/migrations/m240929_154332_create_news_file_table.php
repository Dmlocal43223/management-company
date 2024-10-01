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
            'news_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-news_file-news_id}}',
            '{{%news_file}}',
            'news_id'
        );

        $this->addForeignKey(
            '{{%fk-news_file-news_id}}',
            '{{%news_file}}',
            'news_id',
            '{{%news}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-news_file-file_id}}',
            '{{%news_file}}',
            'file_id'
        );

        $this->addForeignKey(
            '{{%fk-news_file-file_id}}',
            '{{%news_file}}',
            'file_id',
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
            '{{%fk-news_file-file_id}}',
            '{{%news_file}}'
        );

        $this->dropIndex(
            '{{%idx-news_file-file_id}}',
            '{{%news_file}}'
        );

        $this->dropForeignKey(
            '{{%fk-news_file-news_id}}',
            '{{%news_file}}'
        );

        $this->dropIndex(
            '{{%idx-news_file-news_id}}',
            '{{%news_file}}'
        );

        $this->dropTable('{{%news_file}}');
    }
}
