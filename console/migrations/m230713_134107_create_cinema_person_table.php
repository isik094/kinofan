<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_person}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 * - `{{%cinema}}`
 */
class m230713_134107_create_cinema_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_person}}', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer(),
            'cinema_id' => $this->integer(),
            'profession_key' => $this->string(),
        ]);

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-cinema_person-person_id}}',
            '{{%cinema_person}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-cinema_person-person_id}}',
            '{{%cinema_person}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_person-cinema_id}}',
            '{{%cinema_person}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_person-cinema_id}}',
            '{{%cinema_person}}',
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
            '{{%fk-cinema_person-person_id}}',
            '{{%cinema_person}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-cinema_person-person_id}}',
            '{{%cinema_person}}'
        );

        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-cinema_person-cinema_id}}',
            '{{%cinema_person}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_person-cinema_id}}',
            '{{%cinema_person}}'
        );

        $this->dropTable('{{%cinema_person}}');
    }
}
