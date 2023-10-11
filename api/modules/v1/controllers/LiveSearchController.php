<?php

namespace api\modules\v1\controllers;

use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\CinemaLiveSearch;

class LiveSearchController extends ApiController
{
    protected bool $isPrivate = false;

    /**
     * @param string|null $search
     * @return ApiResponse|ApiResponseException
     */
    public function actionIndex(?string $search): ApiResponse|ApiResponseException
    {
        try {
            $model = new CinemaLiveSearch($search);
            $data = $model->search();

            return new ApiResponse(false, $data);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}