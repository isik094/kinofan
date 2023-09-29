<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_hobbies}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%hobbies}}`
 */
class m230928_155613_create_user_hobbies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_hobbies}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'hobbies_id' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_hobbies-user_id}}',
            '{{%user_hobbies}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_hobbies-user_id}}',
            '{{%user_hobbies}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `hobbies_id`
        $this->createIndex(
            '{{%idx-user_hobbies-hobbies_id}}',
            '{{%user_hobbies}}',
            'hobbies_id'
        );

        // add foreign key for table `{{%hobbies}}`
        $this->addForeignKey(
            '{{%fk-user_hobbies-hobbies_id}}',
            '{{%user_hobbies}}',
            'hobbies_id',
            '{{%hobbies}}',
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
            '{{%fk-user_hobbies-user_id}}',
            '{{%user_hobbies}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_hobbies-user_id}}',
            '{{%user_hobbies}}'
        );

        // drops foreign key for table `{{%hobbies}}`
        $this->dropForeignKey(
            '{{%fk-user_hobbies-hobbies_id}}',
            '{{%user_hobbies}}'
        );

        // drops index for column `hobbies_id`
        $this->dropIndex(
            '{{%idx-user_hobbies-hobbies_id}}',
            '{{%user_hobbies}}'
        );

        $this->dropTable('{{%user_hobbies}}');
    }
}
