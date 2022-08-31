<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie_genre}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%movies}}`
 * - `{{%genre}}`
 */
class m220830_084304_create_movie_genre_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie_genre}}', [
            'id' => $this->primaryKey(),
            'movies_id' => $this->integer(),
            'genre_id' => $this->integer(),
        ]);

        // creates index for column `movies_id`
        $this->createIndex(
            '{{%idx-movie_genre-movies_id}}',
            '{{%movie_genre}}',
            'movies_id'
        );

        // add foreign key for table `{{%movies}}`
        $this->addForeignKey(
            '{{%fk-movie_genre-movies_id}}',
            '{{%movie_genre}}',
            'movies_id',
            '{{%movies}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-movie_genre-genre_id}}',
            '{{%movie_genre}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-movie_genre-genre_id}}',
            '{{%movie_genre}}',
            'genre_id',
            '{{%genre}}',
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
            '{{%fk-movie_genre-movies_id}}',
            '{{%movie_genre}}'
        );

        // drops index for column `movies_id`
        $this->dropIndex(
            '{{%idx-movie_genre-movies_id}}',
            '{{%movie_genre}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-movie_genre-genre_id}}',
            '{{%movie_genre}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-movie_genre-genre_id}}',
            '{{%movie_genre}}'
        );

        $this->dropTable('{{%movie_genre}}');
    }
}
