<?php

use yii\db\Migration;

/**
 * Class m230615_124536_jwt_table
 */
class m230615_124536_jwt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `user_refresh_tokens` (
	`user_refresh_tokenID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`urf_userID` INT(10) UNSIGNED NOT NULL,
	`urf_token` VARCHAR(1000) NOT NULL,
	`urf_ip` VARCHAR(50) NOT NULL,
	`urf_user_agent` VARCHAR(1000) NOT NULL,
	`urf_created` DATETIME NOT NULL COMMENT 'UTC',
	PRIMARY KEY (`user_refresh_tokenID`)
)
COMMENT='For JWT authentication process';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230615_124536_jwt_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230615_124536_jwt_table cannot be reverted.\n";

        return false;
    }
    */
}
