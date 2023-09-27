<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;
use common\models\Favorites;
use yii\web\NotFoundHttpException;

trait FavoritesData
{
    use UserData;
    use CinemaData;

    public function favoritesData(): array
    {
        return [
            'user' => new ApiGetter('user', $this->userData()),
            'cinema' => new ApiGetter('cinema', $this->cinemaData()),
        ];
    }

    /**
     * @param int $id
     * @return Favorites
     * @throws NotFoundHttpException
     */
    public function findFavorites(int $id): Favorites
    {
        if (($model = Favorites::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}