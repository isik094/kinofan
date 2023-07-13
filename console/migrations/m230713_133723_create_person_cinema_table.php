<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person_cinema}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 * - `{{%cinema}}`
 */
class m230713_133723_create_person_cinema_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%person_cinema}}', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'cinema_id' => $this->integer(),
            'profession_text' => $this->string(),
        ]);

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-person_cinema-person_id}}',
            '{{%person_cinema}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-person_cinema-person_id}}',
            '{{%person_cinema}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-person_cinema-cinema_id}}',
            '{{%person_cinema}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-person_cinema-cinema_id}}',
            '{{%person_cinema}}',
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
        // drops foreign key for table `{{%person}}`
        $this->dropForeignKey(
            '{{%fk-person_cinema-person_id}}',
            '{{%person_cinema}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-person_cinema-person_id}}',
            '{{%person_cinema}}'
        );

        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-person_cinema-cinema_id}}',
            '{{%person_cinema}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-person_cinema-cinema_id}}',
            '{{%person_cinema}}'
        );

        $this->dropTable('{{%person_cinema}}');
    }
}
