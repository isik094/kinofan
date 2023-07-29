<?php

use yii\db\Migration;

/**
 * Class m230724_154425_update_column_type
 */
class m230724_154425_update_column_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cinema', 'created_at', $this->integer());
        $this->alterColumn('cinema', 'updated_at', $this->integer());
        $this->alterColumn('cinema', 'deleted_at', $this->integer());
        $this->alterColumn('cinema', 'premiere_ru', $this->integer());
        $this->alterColumn('cinema', 'release_date', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230724_154425_update_column_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230724_154425_update_column_type cannot be reverted.\n";

        return false;
    }
    */
}
