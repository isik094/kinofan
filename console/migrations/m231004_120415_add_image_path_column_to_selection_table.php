<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%selection}}`.
 */
class m231004_120415_add_image_path_column_to_selection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%selection}}', 'image_path', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%selection}}', 'image_path');
    }
}
