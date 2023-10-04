<?php

namespace api\modules\v1\controllers;

use Yii;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\SelectionSearch;
use api\modules\v1\traits\SelectionTrait;

class SelectionController extends ApiWithSearchController
{
    use SelectionTrait;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): SelectionSearch
    {
        return new SelectionSearch();
    }

    /**
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->selectionData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @param int $id
     * @return ApiResponse|ApiResponseException
     */
    public function actionView(int $id): ApiResponse|ApiResponseException
    {
        try {
            $model = $this->findSelection($id);
            return new ApiResponse(false, $this->makeObject($model, $this->selectionData()));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}