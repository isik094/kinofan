<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%selection}}`.
 */
class m231004_080253_create_selection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%selection}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%selection}}');
    }
}
