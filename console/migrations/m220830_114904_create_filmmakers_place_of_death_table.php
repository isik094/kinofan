<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%filmmakers_place_of_death}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%filmmakers}}`
 * - `{{%country}}`
 */
class m220830_114904_create_filmmakers_place_of_death_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%filmmakers_place_of_death}}', [
            'id' => $this->primaryKey(),
            'filmmakers_id' => $this->integer(),
            'country_id' => $this->integer(),
        ]);

        // creates index for column `filmmakers_id`
        $this->createIndex(
            '{{%idx-filmmakers_place_of_death-filmmakers_id}}',
            '{{%filmmakers_place_of_death}}',
            'filmmakers_id'
        );

        // add foreign key for table `{{%filmmakers}}`
        $this->addForeignKey(
            '{{%fk-filmmakers_place_of_death-filmmakers_id}}',
            '{{%filmmakers_place_of_death}}',
            'filmmakers_id',
            '{{%filmmakers}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-filmmakers_place_of_death-country_id}}',
            '{{%filmmakers_place_of_death}}',
            'country_id'
        );

        // add foreign key for table `{{%country}}`
        $this->addForeignKey(
            '{{%fk-filmmakers_place_of_death-country_id}}',
            '{{%filmmakers_place_of_death}}',
            'country_id',
            '{{%country}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%filmmakers}}`
        $this->dropForeignKey(
            '{{%fk-filmmakers_place_of_death-filmmakers_id}}',
            '{{%filmmakers_place_of_death}}'
        );

        // drops index for column `filmmakers_id`
        $this->dropIndex(
            '{{%idx-filmmakers_place_of_death-filmmakers_id}}',
            '{{%filmmakers_place_of_death}}'
        );

        // drops foreign key for table `{{%country}}`
        $this->dropForeignKey(
            '{{%fk-filmmakers_place_of_death-country_id}}',
            '{{%filmmakers_place_of_death}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-filmmakers_place_of_death-country_id}}',
            '{{%filmmakers_place_of_death}}'
        );

        $this->dropTable('{{%filmmakers_place_of_death}}');
    }
}
