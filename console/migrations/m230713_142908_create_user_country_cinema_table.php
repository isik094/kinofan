<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_country_cinema}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%country}}`
 */
class m230713_142908_create_user_country_cinema_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_country_cinema}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'country_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_country_cinema-user_id}}',
            '{{%user_country_cinema}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_country_cinema-user_id}}',
            '{{%user_country_cinema}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-user_country_cinema-country_id}}',
            '{{%user_country_cinema}}',
            'country_id'
        );

        // add foreign key for table `{{%country}}`
        $this->addForeignKey(
            '{{%fk-user_country_cinema-country_id}}',
            '{{%user_country_cinema}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_country_cinema-user_id}}',
            '{{%user_country_cinema}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_country_cinema-user_id}}',
            '{{%user_country_cinema}}'
        );

        // drops foreign key for table `{{%country}}`
        $this->dropForeignKey(
            '{{%fk-user_country_cinema-country_id}}',
            '{{%user_country_cinema}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-user_country_cinema-country_id}}',
            '{{%user_country_cinema}}'
        );

        $this->dropTable('{{%user_country_cinema}}');
    }
}
