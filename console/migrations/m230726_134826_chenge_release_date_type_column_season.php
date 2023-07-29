<?php

use yii\db\Migration;

/**
 * Class m230726_134826_chenge_release_date_type_column_season
 */
class m230726_134826_chenge_release_date_type_column_season extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('season', 'release_date', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230726_134826_chenge_release_date_type_column_season cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230726_134826_chenge_release_date_type_column_season cannot be reverted.\n";

        return false;
    }
    */
}
