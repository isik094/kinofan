<?php

use yii\db\Migration;

/**
 * Class m230809_150316_cahnge_created_at_column_type_to_comment_table
 */
class m230809_150316_cahnge_created_at_column_type_to_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('comment', 'created_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230809_150316_cahnge_created_at_column_type_to_comment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230809_150316_cahnge_created_at_column_type_to_comment_table cannot be reverted.\n";

        return false;
    }
    */
}
