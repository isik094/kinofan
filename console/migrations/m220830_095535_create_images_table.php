<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%images}}`.
 */
class m220830_095535_create_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%images}}', [
            'id' => $this->primaryKey(),
            'key' => $this->integer(),
            'type' => $this->integer(),
            'path' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%images}}');
    }
}
