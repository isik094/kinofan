<?php

use yii\db\Migration;

/**
 * Class m230726_101525_chnge_type_column
 */
class m230726_101525_chnge_type_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('person', 'birthday', $this->integer());
        $this->alterColumn('person', 'death', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230726_101525_chnge_type_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230726_101525_chnge_type_column cannot be reverted.\n";

        return false;
    }
    */
}
