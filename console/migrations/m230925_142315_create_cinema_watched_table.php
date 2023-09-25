<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema_watched}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%cinema}}`
 */
class m230925_142315_create_cinema_watched_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema_watched}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'cinema_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-cinema_watched-user_id}}',
            '{{%cinema_watched}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-cinema_watched-user_id}}',
            '{{%cinema_watched}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-cinema_watched-cinema_id}}',
            '{{%cinema_watched}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-cinema_watched-cinema_id}}',
            '{{%cinema_watched}}',
            'cinema_id',
            '{{%cinema}}',
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
            '{{%fk-cinema_watched-user_id}}',
            '{{%cinema_watched}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-cinema_watched-user_id}}',
            '{{%cinema_watched}}'
        );

        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-cinema_watched-cinema_id}}',
            '{{%cinema_watched}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-cinema_watched-cinema_id}}',
            '{{%cinema_watched}}'
        );

        $this->dropTable('{{%cinema_watched}}');
    }
}
