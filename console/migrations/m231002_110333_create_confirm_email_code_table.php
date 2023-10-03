<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%confirm_email_code}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m231002_110333_create_confirm_email_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%confirm_email_code}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'email' => $this->string(),
            'code' => $this->string(),
            'created_at' => $this->integer(),
            'accepted_at' => $this->integer(),
            'attempt' => $this->integer()->defaultValue(0),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-confirm_email_code-user_id}}',
            '{{%confirm_email_code}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-confirm_email_code-user_id}}',
            '{{%confirm_email_code}}',
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
            '{{%fk-confirm_email_code-user_id}}',
            '{{%confirm_email_code}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-confirm_email_code-user_id}}',
            '{{%confirm_email_code}}'
        );

        $this->dropTable('{{%confirm_email_code}}');
    }
}
