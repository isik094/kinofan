<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%movies}}`.
 */
class m220830_132712_add_kp_id_column_to_movies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%movies}}', 'kp_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%movies}}', 'kp_id');
    }
}
