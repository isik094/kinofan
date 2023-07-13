<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorites}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%cinema}}`
 */
class m230713_142048_create_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorites}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'cinema_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-favorites-user_id}}',
            '{{%favorites}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-favorites-user_id}}',
            '{{%favorites}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `cinema_id`
        $this->createIndex(
            '{{%idx-favorites-cinema_id}}',
            '{{%favorites}}',
            'cinema_id'
        );

        // add foreign key for table `{{%cinema}}`
        $this->addForeignKey(
            '{{%fk-favorites-cinema_id}}',
            '{{%favorites}}',
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
            '{{%fk-favorites-user_id}}',
            '{{%favorites}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-favorites-user_id}}',
            '{{%favorites}}'
        );

        // drops foreign key for table `{{%cinema}}`
        $this->dropForeignKey(
            '{{%fk-favorites-cinema_id}}',
            '{{%favorites}}'
        );

        // drops index for column `cinema_id`
        $this->dropIndex(
            '{{%idx-favorites-cinema_id}}',
            '{{%favorites}}'
        );

        $this->dropTable('{{%favorites}}');
    }
}
