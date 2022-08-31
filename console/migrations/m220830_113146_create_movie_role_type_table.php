<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movie_role_type}}`.
 */
class m220830_113146_create_movie_role_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movie_role_type}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%movie_role_type}}');
    }
}
