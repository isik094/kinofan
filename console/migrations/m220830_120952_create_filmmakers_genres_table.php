<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%filmmakers_genres}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%filmmakers}}`
 * - `{{%genre}}`
 */
class m220830_120952_create_filmmakers_genres_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%filmmakers_genres}}', [
            'id' => $this->primaryKey(),
            'filmmakers_id' => $this->integer(),
            'genre_id' => $this->integer(),
        ]);

        // creates index for column `filmmakers_id`
        $this->createIndex(
            '{{%idx-filmmakers_genres-filmmakers_id}}',
            '{{%filmmakers_genres}}',
            'filmmakers_id'
        );

        // add foreign key for table `{{%filmmakers}}`
        $this->addForeignKey(
            '{{%fk-filmmakers_genres-filmmakers_id}}',
            '{{%filmmakers_genres}}',
            'filmmakers_id',
            '{{%filmmakers}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genre_id`
        $this->createIndex(
            '{{%idx-filmmakers_genres-genre_id}}',
            '{{%filmmakers_genres}}',
            'genre_id'
        );

        // add foreign key for table `{{%genre}}`
        $this->addForeignKey(
            '{{%fk-filmmakers_genres-genre_id}}',
            '{{%filmmakers_genres}}',
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
        // drops foreign key for table `{{%filmmakers}}`
        $this->dropForeignKey(
            '{{%fk-filmmakers_genres-filmmakers_id}}',
            '{{%filmmakers_genres}}'
        );

        // drops index for column `filmmakers_id`
        $this->dropIndex(
            '{{%idx-filmmakers_genres-filmmakers_id}}',
            '{{%filmmakers_genres}}'
        );

        // drops foreign key for table `{{%genre}}`
        $this->dropForeignKey(
            '{{%fk-filmmakers_genres-genre_id}}',
            '{{%filmmakers_genres}}'
        );

        // drops index for column `genre_id`
        $this->dropIndex(
            '{{%idx-filmmakers_genres-genre_id}}',
            '{{%filmmakers_genres}}'
        );

        $this->dropTable('{{%filmmakers_genres}}');
    }
}
