<?php

namespace common\models;

/**
 * This is the model class for table "selection".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property CinemaSelection[] $cinemaSelections
 */
class Selection extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'selection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[CinemaSelections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaSelections(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CinemaSelection::className(), ['selection_id' => 'id']);
    }

    /**
     * @brief Список фильмов
     * @return array
     * @throws \Exception
     */
    public function getCinemaList(): array
    {
        $cinemaArray = [];
        foreach ($this->cinemaSelections as $cinemaSelection) {
            $cinema = $cinemaSelection->cinema;
            $cinemaArray[] = [
                'id' => $cinema->id,
                'id_kp' => $cinema->id_kp,
                'name_ru' => $cinema->name_ru,
                'name_original' => $cinema->name_original,
                'poster_url' => $cinema->uploadsLink('poster_url'),
                'poster_url_preview' => $cinema->uploadsLink('poster_url_preview'),
                'rating_kinopoisk' => $cinema->rating_kinopoisk,
                'year' => $cinema->year,
                'film_length' => $cinema->film_length,
                'slogan' => $cinema->slogan,
                'description' => $cinema->description,
                'type' => $cinema->type,
                'rating_mpaa' => $cinema->rating_mpaa,
                'rating_age_limits' => $cinema->rating_age_limits,
                'start_year' => $cinema->start_year,
                'end_year' => $cinema->end_year,
                'completed' => $cinema->completed,
                'created_at' => $cinema->created_at,
                'premiere_ru' => $cinema->premiere_ru,
                'release_date' => $cinema->release_date,
                'rating_imdb' => $cinema->rating_imdb,
            ];
        }

        return $cinemaArray;
    }
}
