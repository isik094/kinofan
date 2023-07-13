<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person_fact}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 */
class m230713_114755_create_person_fact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%person_fact}}', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer()->notNull(),
            'text' => $this->string(600),
        ]);

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-person_fact-person_id}}',
            '{{%person_fact}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-person_fact-person_id}}',
            '{{%person_fact}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%person}}`
        $this->dropForeignKey(
            '{{%fk-person_fact-person_id}}',
            '{{%person_fact}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-person_fact-person_id}}',
            '{{%person_fact}}'
        );

        $this->dropTable('{{%person_fact}}');
    }
}
