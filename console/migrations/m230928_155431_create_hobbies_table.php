<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%hobbies}}`.
 */
class m230928_155431_create_hobbies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%hobbies}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%hobbies}}');
    }
}
