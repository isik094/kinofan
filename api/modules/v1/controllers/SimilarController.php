<?php

namespace api\modules\v1\controllers;

use Yii;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\SimilarSearch;
use api\modules\v1\traits\SimilarData;

class SimilarController extends ApiWithSearchController
{
    protected bool $isPrivate = false;

    use SimilarData;

    /**
     * @inheritDoc
     */
    public function getSearchModel()
    {
        return new SimilarSearch();
    }

    /**
     * @brief Получить список похожих фильмов
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->similarData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}