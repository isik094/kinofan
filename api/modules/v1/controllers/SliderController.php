<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\traits\SliderTrait;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\SliderSearch;

class SliderController extends ApiWithSearchController
{
    use SliderTrait;

    protected bool $isPrivate = false;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): SliderSearch
    {
        return new SliderSearch();
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->sliderData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}