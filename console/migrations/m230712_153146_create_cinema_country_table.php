<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_country}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 * - `{{%country}}`
 */
class m230712_153146_create_cinema_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_country}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'country_id' => $this->integer(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_country-cinema_id}}',
            '{{%cinema_country}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_country-cinema_id}}',
            '{{%cinema_country}}',
            'cinema_id',
            '{{%cinema}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-cinema_country-country_id}}',
            '{{%cinema_country}}',
            'country_id'
        );

        // add foreign key for table `{{%country}}`
        $this->addForeignKey(
            '{{%fk-cinema_country-country_id}}',
            '{{%cinema_country}}',
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
        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-cinema_country-cinema_id}}',
            '{{%cinema_country}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_country-cinema_id}}',
            '{{%cinema_country}}'
        );

        // drops foreign key for table `{{%country}}`
        $this->dropForeignKey(
            '{{%fk-cinema_country-country_id}}',
            '{{%cinema_country}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-cinema_country-country_id}}',
            '{{%cinema_country}}'
        );

        $this->dropTable('{{%cinema_country}}');
    }
}
