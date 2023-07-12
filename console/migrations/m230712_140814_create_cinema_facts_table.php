<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_facts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 */
class m230712_140814_create_cinema_facts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_facts}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'text' => $this->string(600),
            'type' => $this->string(),
            'spoiler' => $this->tinyInteger(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_facts-cinema_id}}',
            '{{%cinema_facts}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_facts-cinema_id}}',
            '{{%cinema_facts}}',
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
            '{{%fk-cinema_facts-cinema_id}}',
            '{{%cinema_facts}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_facts-cinema_id}}',
            '{{%cinema_facts}}'
        );

        $this->dropTable('{{%cinema_facts}}');
    }
}
