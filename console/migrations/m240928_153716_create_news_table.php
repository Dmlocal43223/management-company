<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m240928_153716_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-news-author_id}}',
            '{{%news}}',
            'author_id'
        );

        $this->addForeignKey(
            '{{%fk-news-author_id}}',
            '{{%news}}',
            'author_id',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-news-title}}',
            '{{%news}}',
            'title'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-news-title}}',
            '{{%news}}'
        );

        $this->dropForeignKey(
            '{{%fk-news-author_id}}',
            '{{%news}}'
        );

        $this->dropIndex(
            '{{%idx-news-author_id}}',
            '{{%news}}'
        );

        $this->dropTable('{{%news}}');
    }
}
