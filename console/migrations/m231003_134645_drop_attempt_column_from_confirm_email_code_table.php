<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%confirm_email_code}}`.
 */
class m231003_134645_drop_attempt_column_from_confirm_email_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%confirm_email_code}}', 'attempt');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%confirm_email_code}}', 'attempt', $this->integer());
    }
}
