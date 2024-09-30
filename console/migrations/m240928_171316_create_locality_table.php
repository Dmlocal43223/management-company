<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%locality}}`.
 */
class m240928_171316_create_locality_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%locality}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'region_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->createIndex(
            '{{%idx-locality-name-region_id}}',
            '{{%locality}}',
            ['name', 'region_id'],
            true
        );

        $this->createIndex(
            '{{%idx-locality-region_id}}',
            '{{%locality}}',
            'region_id'
        );

        $this->addForeignKey(
            '{{%fk-locality-region_id}}',
            '{{%locality}}',
            'region_id',
            '{{%region}}',
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
            '{{%fk-locality-region_id}}',
            '{{%locality}}'
        );

        $this->dropIndex(
            '{{%idx-locality-region_id}}',
            '{{%locality}}'
        );

        $this->dropIndex(
            '{{%idx-locality-name-region_id}}',
            '{{%locality}}'
        );

        $this->dropTable('{{%locality}}');
    }
}
