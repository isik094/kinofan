<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sequel_and_prequel}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230713_122556_create_sequel_and_prequel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sequel_and_prequel}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer()->notNull(),
            'relation_type' => $this->string(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-sequel_and_prequel-cinema_id}}',
            '{{%sequel_and_prequel}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-sequel_and_prequel-cinema_id}}',
            '{{%sequel_and_prequel}}',
            'cinema_id',
            '{{%cinema}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-sequel_and_prequel-cinema_id}}',
            '{{%sequel_and_prequel}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-sequel_and_prequel-cinema_id}}',
            '{{%sequel_and_prequel}}'
        );

        $this->dropTable('{{%sequel_and_prequel}}');
    }
}
