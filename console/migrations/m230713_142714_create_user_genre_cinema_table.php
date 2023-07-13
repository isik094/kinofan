<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_genre_cinema}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%genre}}`
 */
class m230713_142714_create_user_genre_cinema_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_genre_cinema}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'genre_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_genre_cinema-user_id}}',
            '{{%user_genre_cinema}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_genre_cinema-user_id}}',
            '{{%user_genre_cinema}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-user_genre_cinema-genre_id}}',
            '{{%user_genre_cinema}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-user_genre_cinema-genre_id}}',
            '{{%user_genre_cinema}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_genre_cinema-user_id}}',
            '{{%user_genre_cinema}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_genre_cinema-user_id}}',
            '{{%user_genre_cinema}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-user_genre_cinema-genre_id}}',
            '{{%user_genre_cinema}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-user_genre_cinema-genre_id}}',
            '{{%user_genre_cinema}}'
        );

        $this->dropTable('{{%user_genre_cinema}}');
    }
}
