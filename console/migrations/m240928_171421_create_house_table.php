<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%house}}`.
 */
class m240928_171421_create_house_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%house}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string()->notNull(),
            'street_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-house-number-street_id}}',
            '{{%house}}',
            ['number', 'street_id'],
            true
        );

        $this->createIndex(
            '{{%idx-house-street_id}}',
            '{{%house}}',
            'street_id'
        );

        $this->addForeignKey(
            '{{%fk-house-street_id}}',
            '{{%house}}',
            'street_id',
            '{{%street}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-house-deleted}}',
            '{{%house}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-house-deleted}}',
            '{{%house}}'
        );

        $this->dropForeignKey(
            '{{%fk-house-street_id}}',
            '{{%house}}'
        );

        $this->dropIndex(
            '{{%idx-house-street_id}}',
            '{{%house}}'
        );

        $this->dropIndex(
            '{{%idx-house-number-street_id}}',
            '{{%house}}'
        );

        $this->dropTable('{{%house}}');
    }
}
