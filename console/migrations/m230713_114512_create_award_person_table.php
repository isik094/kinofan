<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%award_person}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%award}}`
 * - `{{%person}}`
 */
class m230713_114512_create_award_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%award_person}}', [
            'id' => $this->primaryKey(),
            'award_id' => $this->integer()->notNull(),
            'person_id' => $this->integer()->notNull(),
            'age' => $this->integer(),
            'profession' => $this->string(400),
        ]);

        // creates index for column `award_id`
        $this->createIndex(
            '{{%idx-award_person-award_id}}',
            '{{%award_person}}',
            'award_id'
        );

        // add foreign key for table `{{%award}}`
        $this->addForeignKey(
            '{{%fk-award_person-award_id}}',
            '{{%award_person}}',
            'award_id',
            '{{%award}}',
            'id',
            'CASCADE'
        );

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-award_person-person_id}}',
            '{{%award_person}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-award_person-person_id}}',
            '{{%award_person}}',
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
        // drops foreign key for table `{{%award}}`
        $this->dropForeignKey(
            '{{%fk-award_person-award_id}}',
            '{{%award_person}}'
        );

        // drops index for column `award_id`
        $this->dropIndex(
            '{{%idx-award_person-award_id}}',
            '{{%award_person}}'
        );

        // drops foreign key for table `{{%person}}`
        $this->dropForeignKey(
            '{{%fk-award_person-person_id}}',
            '{{%award_person}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-award_person-person_id}}',
            '{{%award_person}}'
        );

        $this->dropTable('{{%award_person}}');
    }
}
