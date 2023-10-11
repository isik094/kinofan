<?php

namespace api\modules\v1\traits;

use api\components\ApiFunction;
use common\models\Cinema;
use yii\web\NotFoundHttpException;

trait CinemaData
{
    use GenreTrait;

    /**
     * @brief Cinema Data
     * @return array
     */
    public function cinemaData(): array
    {
        return [
            'id',
            'id_kp',
            'name_ru',
            'name_original',
            'poster_url' => new ApiFunction('uploadsLink', ['poster_url'], null, false, null),
            'poster_url_preview' => new ApiFunction('uploadsLink', ['poster_url_preview'], null, false, null),
            'rating_kinopoisk',
            'year',
            'film_length',
            'slogan',
            'description',
            'type',
            'rating_mpaa',
            'rating_age_limits',
            'start_year',
            'end_year',
            'completed',
            'created_at',
            'premiere_ru',
            'release_date',
            'rating_imdb',
            'genres',
            'countries',
        ];
    }

    /**
     * @brief Найти кино
     * @param int $id
     * @return Cinema|null
     * @throws NotFoundHttpException
     */
    public function findCinema(int $id): ?Cinema
    {
        if (($model = Cinema::findOne(['id' => $id, 'deleted_at' => null])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}