<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%street}}`.
 */
class m240928_171416_create_street_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%street}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'locality_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-unique-street-locality}}',
            '{{%street}}',
            ['locality_id', 'name'],
            true
        );

        $this->addForeignKey(
            '{{%fk-street-locality_id}}',
            '{{%street}}',
            'locality_id',
            '{{%locality}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->createIndex(
            '{{%idx-street-deleted}}',
            '{{%street}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-street-deleted}}',
            '{{%street}}'
        );

        $this->dropForeignKey(
            '{{%fk-street-locality_id}}',
            '{{%street}}'
        );

        $this->dropIndex(
            '{{%idx-unique-street-locality}}',
            '{{%street}}'
        );

        $this->dropTable('{{%street}}');
    }
}
