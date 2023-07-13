<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spouse}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 * - `{{%person}}`
 */
class m230713_120211_create_spouse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spouse}}', [
            'id' => $this->primaryKey(),
            'person_id' => $this->integer()->notNull(),
            'spouse_id' => $this->integer(),
            'divorced' => $this->tinyInteger(),
            'divorced_reason' => $this->string(),
            'children' => $this->integer(),
            'relation' => $this->string(),
        ]);

        // creates index for column `person_id`
        $this->createIndex(
            '{{%idx-spouse-person_id}}',
            '{{%spouse}}',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-spouse-person_id}}',
            '{{%spouse}}',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );

        // creates index for column `spouse_id`
        $this->createIndex(
            '{{%idx-spouse-spouse_id}}',
            '{{%spouse}}',
            'spouse_id'
        );

        // add foreign key for table `{{%person}}`
        $this->addForeignKey(
            '{{%fk-spouse-spouse_id}}',
            '{{%spouse}}',
            'spouse_id',
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
            '{{%fk-spouse-person_id}}',
            '{{%spouse}}'
        );

        // drops index for column `person_id`
        $this->dropIndex(
            '{{%idx-spouse-person_id}}',
            '{{%spouse}}'
        );

        // drops foreign key for table `{{%person}}`
        $this->dropForeignKey(
            '{{%fk-spouse-spouse_id}}',
            '{{%spouse}}'
        );

        // drops index for column `spouse_id`
        $this->dropIndex(
            '{{%idx-spouse-spouse_id}}',
            '{{%spouse}}'
        );

        $this->dropTable('{{%spouse}}');
    }
}
