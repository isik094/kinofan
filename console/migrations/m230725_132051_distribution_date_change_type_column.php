<?php

use yii\db\Migration;

/**
 * Class m230725_132051_distribution_date_change_type_column
 */
class m230725_132051_distribution_date_change_type_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('distribution', 'date', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230725_132051_distribution_date_change_type_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230725_132051_distribution_date_change_type_column cannot be reverted.\n";

        return false;
    }
    */
}
