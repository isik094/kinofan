<?php

use yii\db\Migration;

/**
 * Class m230616_114240_rename_column_to_user_refresh_token_and_add_foreign_key
 */
class m230616_114240_rename_column_to_user_refresh_token_and_add_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `user_refresh_tokens`
            CHANGE `user_refresh_tokenID` `user_refresh_token_id` int(10) unsigned NOT NULL AUTO_INCREMENT FIRST,
            CHANGE `urf_userID` `user_id` int(11) NOT NULL AFTER `user_refresh_token_id`,
            CHANGE `urf_token` `token` varchar(1000) COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `user_id`,
            CHANGE `urf_ip` `ip` varchar(50) COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `token`,
            CHANGE `urf_user_agent` `user_agent` varchar(1000) COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `ip`,
            CHANGE `urf_created` `created` datetime NOT NULL COMMENT 'UTC' AFTER `user_agent`,
            ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT;");

        $this->execute("ALTER TABLE `user_refresh_tokens`
            CHANGE `created` `created_at` int NOT NULL COMMENT 'UTC' AFTER `user_agent`;");

        $this->execute("ALTER TABLE `user_refresh_tokens`
            CHANGE `user_refresh_token_id` `id` int(10) unsigned NOT NULL AUTO_INCREMENT FIRST;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230616_114240_rename_column_to_user_refresh_token_and_add_foreign_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230616_114240_rename_column_to_user_refresh_token_and_add_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
