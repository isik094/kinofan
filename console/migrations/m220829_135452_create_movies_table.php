<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%movies}}`.
 */
class m220829_135452_create_movies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%movies}}', [
            'id' => $this->primaryKey(),
            'category' => $this->integer(),
            'title_rus' => $this->string(),
            'title_eng' => $this->string(),
            'short_description' => $this->string(455),
            'production_year' => $this->string(),
            'tagline' => $this->string(),
            'age' => $this->string(),
            'rating_mpaa' => $this->string(),
            'rating_mpaa_designation' => $this->string(),
            'time' => $this->string(),
            'description' => $this->text(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%movies}}');
    }
}
