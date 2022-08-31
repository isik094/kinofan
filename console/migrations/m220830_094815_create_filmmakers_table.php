<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%filmmakers}}`.
 */
class m220830_094815_create_filmmakers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%filmmakers}}', [
            'id' => $this->primaryKey(),
            'title_rus' => $this->string(),
            'title_eng' => $this->string(),
            'growth' => $this->string(),
            'date_of_birth' => $this->string(),
            'date_of_death' => $this->string(),
            'total_films' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%filmmakers}}');
    }
}
