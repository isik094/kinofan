<?php

use yii\db\Migration;

/**
 * Class m230927_123646_change_type_to_birthday_column_for_profile_table
 */
class m230927_123646_change_type_to_birthday_column_for_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('profile', 'birthday', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230927_123646_change_type_to_birthday_column_for_profile_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230927_123646_change_type_to_birthday_column_for_profile_table cannot be reverted.\n";

        return false;
    }
    */
}
