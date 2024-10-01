<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%region}}`.
 */
class m240928_171256_create_region_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%region}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

//        $this->createIndex(
//            '{{%idx-region-name}}',
//            '{{%region}}',
//            'name'
//        );

        $this->createIndex(
            '{{%idx-region-deleted}}',
            '{{%region}}',
            'deleted'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-region-deleted}}',
            '{{%region}}'
        );

//        $this->dropIndex(
//            '{{%idx-region-name}}',
//            '{{%region}}'
//        );

        $this->dropTable('{{%region}}');
    }
}
