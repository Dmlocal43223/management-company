<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apartment}}`.
 */
class m240928_172026_create_apartment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apartment}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string()->notNull(),
            'house_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-apartment-number-house_id}}',
            '{{%apartment}}',
            ['number', 'house_id'],
            true
        );

        $this->createIndex(
            '{{%idx-apartment-house_id}}',
            '{{%apartment}}',
            'house_id'
        );

        $this->addForeignKey(
            '{{%fk-apartment-house_id}}',
            '{{%apartment}}',
            'house_id',
            '{{%house}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-apartment-deleted}}',
            '{{%apartment}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-apartment-deleted}}',
            '{{%apartment}}'
        );

        $this->dropForeignKey(
            '{{%fk-apartment-house_id}}',
            '{{%apartment}}'
        );

        $this->dropIndex(
            '{{%idx-apartment-house_id}}',
            '{{%apartment}}'
        );

        $this->dropIndex(
            '{{%idx-apartment-number-house_id}}',
            '{{%apartment}}'
        );

        $this->dropTable('{{%apartment}}');
    }
}
