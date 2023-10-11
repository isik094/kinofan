<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%compilation}}`.
 */
class m231009_115118_drop_compilation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%compilation}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%compilation}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
