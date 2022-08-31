<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie_countries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%movies}}`
 * - `{{%country}}`
 */
class m220830_084131_create_movie_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie_countries}}', [
            'id' => $this->primaryKey(),
            'movies_id' => $this->integer(),
            'country_id' => $this->integer(),
        ]);

        // creates index for column `movies_id`
        $this->createIndex(
            '{{%idx-movie_countries-movies_id}}',
            '{{%movie_countries}}',
            'movies_id'
        );

        // add foreign key for table `{{%movies}}`
        $this->addForeignKey(
            '{{%fk-movie_countries-movies_id}}',
            '{{%movie_countries}}',
            'movies_id',
            '{{%movies}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-movie_countries-country_id}}',
            '{{%movie_countries}}',
            'country_id'
        );

        // add foreign key for table `{{%country}}`
        $this->addForeignKey(
            '{{%fk-movie_countries-country_id}}',
            '{{%movie_countries}}',
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
        // drops foreign key for table `{{%movies}}`
        $this->dropForeignKey(
            '{{%fk-movie_countries-movies_id}}',
            '{{%movie_countries}}'
        );

        // drops index for column `movies_id`
        $this->dropIndex(
            '{{%idx-movie_countries-movies_id}}',
            '{{%movie_countries}}'
        );

        // drops foreign key for table `{{%country}}`
        $this->dropForeignKey(
            '{{%fk-movie_countries-country_id}}',
            '{{%movie_countries}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-movie_countries-country_id}}',
            '{{%movie_countries}}'
        );

        $this->dropTable('{{%movie_countries}}');
    }
}
