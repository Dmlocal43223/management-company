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
            'street' => $this->string()->notNull(),
            'locality_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-house-number-street-locality_id}}',
            '{{%house}}',
            ['number', 'street', 'locality_id'],
            true
        );

        $this->createIndex(
            '{{%idx-house-locality_id}}',
            '{{%house}}',
            'locality_id'
        );

        $this->addForeignKey(
            '{{%fk-house-locality_id}}',
            '{{%house}}',
            'locality_id',
            '{{%locality}}',
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
            '{{%fk-house-locality_id}}',
            '{{%house}}'
        );

        $this->dropIndex(
            '{{%idx-house-locality_id}}',
            '{{%house}}'
        );

        $this->dropIndex(
            '{{%idx-house-number-street-locality_id}}',
            '{{%house}}'
        );

        $this->dropTable('{{%house}}');
    }
}
