<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%compilation}}`.
 */
class m230713_092626_create_compilation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%compilation}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%compilation}}');
    }
}
