<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\search\CinemaWatchedSearch;
use api\modules\v1\traits\CinemaWatchedTrait;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\CinemaWatchedForm;
use common\models\User;

class CinemaWatchedController extends ApiWithSearchController
{
    use CinemaWatchedTrait;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): CinemaWatchedSearch
    {
        return new CinemaWatchedSearch();
    }

    /**
     * @brief Список просмотренных
     * @param int|null $page
     * @param int $limit
     * @param string|null $sort
     * @return ApiResponse|ApiResponseException
     */
    public function actionIndex(int $page = null, int $limit = 20, string $sort = null): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $searchModel = $this->getSearchModel();
            $query = $searchModel->search(Yii::$app->request->get(), $user);

            $apiSearch = $this->searchAndSort($page, $limit, $sort, $query);

            $data = $this->makeObjects($apiSearch->query->all(), $this->cinemaWatchedData());

            return new ApiResponse(
                false,
                new ApiDataPagination($data, $apiSearch->lastPage)
            );
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @brief Метод для создания записей о просмотренных кино пользователем
     * @return ApiResponse|ApiResponseException
     */
    public function actionCreate(): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $model = new CinemaWatchedForm(['user' => $user]);
            $model->cinema_ids = Yii::$app->request->post('cinema_ids');

            if ($model->create()) {
                return new ApiResponse(false, true);
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}