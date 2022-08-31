<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%filmmaking_career}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%filmmakers}}`
 * - `{{%movie_role_type}}`
 */
class m220830_113717_create_filmmaking_career_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%filmmaking_career}}', [
            'id' => $this->primaryKey(),
            'filmmakers_id' => $this->integer(),
            'movie_role_type_id' => $this->integer(),
        ]);

        // creates index for column `filmmakers_id`
        $this->createIndex(
            '{{%idx-filmmaking_career-filmmakers_id}}',
            '{{%filmmaking_career}}',
            'filmmakers_id'
        );

        // add foreign key for table `{{%filmmakers}}`
        $this->addForeignKey(
            '{{%fk-filmmaking_career-filmmakers_id}}',
            '{{%filmmaking_career}}',
            'filmmakers_id',
            '{{%filmmakers}}',
            'id',
            'CASCADE'
        );

        // creates index for column `movie_role_type_id`
        $this->createIndex(
            '{{%idx-filmmaking_career-movie_role_type_id}}',
            '{{%filmmaking_career}}',
            'movie_role_type_id'
        );

        // add foreign key for table `{{%movie_role_type}}`
        $this->addForeignKey(
            '{{%fk-filmmaking_career-movie_role_type_id}}',
            '{{%filmmaking_career}}',
            'movie_role_type_id',
            '{{%movie_role_type}}',
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
            '{{%fk-filmmaking_career-filmmakers_id}}',
            '{{%filmmaking_career}}'
        );

        // drops index for column `filmmakers_id`
        $this->dropIndex(
            '{{%idx-filmmaking_career-filmmakers_id}}',
            '{{%filmmaking_career}}'
        );

        // drops foreign key for table `{{%movie_role_type}}`
        $this->dropForeignKey(
            '{{%fk-filmmaking_career-movie_role_type_id}}',
            '{{%filmmaking_career}}'
        );

        // drops index for column `movie_role_type_id`
        $this->dropIndex(
            '{{%idx-filmmaking_career-movie_role_type_id}}',
            '{{%filmmaking_career}}'
        );

        $this->dropTable('{{%filmmaking_career}}');
    }
}
