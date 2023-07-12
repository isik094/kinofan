<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_genre}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 * - `{{%genre}}`
 */
class m230712_132813_create_cinema_genre_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_genre}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'genre_id' => $this->integer(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_genre-cinema_id}}',
            '{{%cinema_genre}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_genre-cinema_id}}',
            '{{%cinema_genre}}',
            'cinema_id',
            '{{%cinema}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-cinema_genre-genre_id}}',
            '{{%cinema_genre}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-cinema_genre-genre_id}}',
            '{{%cinema_genre}}',
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
        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-cinema_genre-cinema_id}}',
            '{{%cinema_genre}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_genre-cinema_id}}',
            '{{%cinema_genre}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-cinema_genre-genre_id}}',
            '{{%cinema_genre}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-cinema_genre-genre_id}}',
            '{{%cinema_genre}}'
        );

        $this->dropTable('{{%cinema_genre}}');
    }
}
