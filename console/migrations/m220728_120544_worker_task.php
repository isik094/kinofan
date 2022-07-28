<?php

use yii\db\Migration;

/**
 * Class m220728_120544_worker_task
 */
class m220728_120544_worker_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `worker_task` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `class` text NOT NULL,
  `properties` text NOT NULL,
  `created_at` int NOT NULL,
  `started_at` int NULL,
  `completed_at` int NULL,
  `fail_time` int NULL
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';");

        $this->execute("ALTER TABLE `worker_task`
ADD INDEX `completed_at` (`completed_at`);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220728_120544_worker_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220728_120544_worker_task cannot be reverted.\n";

        return false;
    }
    */
}
