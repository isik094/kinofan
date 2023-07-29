<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sequel_and_prequel}}`.
 */
class m230727_165254_add_id_kp_column_to_sequel_and_prequel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sequel_and_prequel}}', 'id_kp', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sequel_and_prequel}}', 'id_kp');
    }
}
