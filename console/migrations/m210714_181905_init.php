<?php

use yii\db\Migration;

/**
 * Class m210714_181905_init
 */
class m210714_181905_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `message` text CHARACTER SET utf8mb4,
  `trace` text CHARACTER SET utf8mb4,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->execute("CREATE TABLE `api_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method` varchar(255) DEFAULT NULL,
  `get` text,
  `post` text,
  `files` text,
  `headers` text,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $this->execute("ALTER TABLE `user`
ADD `access_token` varchar(255) COLLATE 'utf8_unicode_ci' NULL;");

        $this->execute("CREATE TABLE `ip_block` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip` varchar(255) NOT NULL,
  `created_at` int NOT NULL
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210714_181905_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210714_181905_init cannot be reverted.\n";

        return false;
    }
    */
}
