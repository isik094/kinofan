<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%person}}`.
 */
class m230713_114022_create_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%person}}', [
            'id' => $this->primaryKey(),
            'person_kp_id' => $this->integer()->notNull(),
            'web_url' => $this->string(),
            'name_ru' => $this->string(),
            'name_en' => $this->string(),
            'sex' => $this->string(),
            'poster_url' => $this->string(),
            'growth' => $this->integer(),
            'birthday' => $this->timestamp(),
            'death' => $this->timestamp(),
            'age' => $this->integer(),
            'birthplace' => $this->string(),
            'deathplace' => $this->string(),
            'has_awards' => $this->integer(),
            'profession' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%person}}');
    }
}
