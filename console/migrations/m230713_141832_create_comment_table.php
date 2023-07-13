<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cinema}}`
 * - `{{%user}}`
 */
class m230713_141832_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'cinema_id' => $this->integer(),
            'user_id' => $this->integer(),
            'parent_id' => $this->integer()->notNull(),
            'text' => $this->string(600),
            'created_at' => $this->timestamp(),
        ]);

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-comment-cinema_id}}',
            '{{%comment}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-comment-cinema_id}}',
            '{{%comment}}',
            'cinema_id',
            '{{%cinema}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-comment-user_id}}',
            '{{%comment}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-comment-user_id}}',
            '{{%comment}}',
            'user_id',
            '{{%user}}',
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
            '{{%fk-comment-cinema_id}}',
            '{{%comment}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-comment-cinema_id}}',
            '{{%comment}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-comment-user_id}}',
            '{{%comment}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-comment-user_id}}',
            '{{%comment}}'
        );

        $this->dropTable('{{%comment}}');
    }
}
