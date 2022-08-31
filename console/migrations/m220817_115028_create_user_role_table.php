<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_role}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m220817_115028_create_user_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_role}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'role' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_role-user_id}}',
            '{{%user_role}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_role-user_id}}',
            '{{%user_role}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_role-user_id}}',
            '{{%user_role}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_role-user_id}}',
            '{{%user_role}}'
        );

        $this->dropTable('{{%user_role}}');
    }
}
