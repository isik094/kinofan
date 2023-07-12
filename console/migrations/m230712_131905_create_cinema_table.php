<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cinema}}`.
 */
class m230712_131905_create_cinema_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cinema}}', [
            'id' => $this->primaryKey(),
            'id_kp' => $this->integer(),
            'name_ru' => $this->string(),
            'name_original' => $this->string(),
            'poster_url' => $this->string(),
            'poster_url_preview' => $this->string(),
            'rating_kinopoisk' => $this->double(),
            'year' => $this->integer(),
            'film_length' => $this->integer(),
            'slogan' => $this->string(),
            'description' => $this->text(),
            'type' => $this->string(),
            'rating_mpaa' => $this->string(),
            'rating_age_limits' => $this->string(),
            'start_year' => $this->integer(),
            'end_year' => $this->integer(),
            'serial' => $this->tinyInteger(),
            'completed' => $this->tinyInteger(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'deleted_at' => $this->timestamp(),
            'premiere_ru' => $this->timestamp(),
            'release_date' => $this->timestamp(),
            'rating_imdb' => $this->double(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cinema}}');
    }
}
