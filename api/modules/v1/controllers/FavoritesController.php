<?php

namespace api\modules\v1\controllers;

use Yii;
use api\components\ApiDataPagination;
use api\modules\v1\traits\CinemaData;
use api\modules\v1\traits\FavoritesData;
use common\models\User;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\AddFavoritesForm;
use api\modules\v1\models\search\FavoritesSearch;

class FavoritesController extends ApiWithSearchController
{
    use FavoritesData;
    use CinemaData;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): FavoritesSearch
    {
        return new FavoritesSearch();
    }

    /**
     * @brief Получить список избранных
     * @param int|null $page
     * @param int $limit
     * @param string|null $sort
     * @return ApiResponse|ApiResponseException
     */
    public function actionIndex(int $page = null, int $limit = 20, string $sort = null): ApiResponse|ApiResponseException
    {
        try {
            $searchModel = $this->getSearchModel();
            $query = $searchModel->search(Yii::$app->request->get());

            $apiSearch = $this->searchAndSort($page, $limit, $sort, $query);

            $data = $this->makeObjects($apiSearch->query->all(), $this->favoritesData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @brief Добавить в избранное
     * @return ApiResponse|ApiResponseException
     */
    public function actionAdd(): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $cinema = $this->findCinema(Yii::$app->request->post('cinema_id'));
            $model = new AddFavoritesForm(['user' => $user]);
            $model->cinema_id = $cinema->id;

            if ($favorites = $model->create()) {
                return new ApiResponse(false, $favorites);
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @brief Удалить из избранных
     * @param int $id
     * @return ApiResponse|ApiResponseException
     * @throws \Throwable
     */
    public function actionDelete(int $id): ApiResponse|ApiResponseException
    {
        try {
            $model = $this->findFavorites($id);
            return new ApiResponse(false, (bool)$model->delete());
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}