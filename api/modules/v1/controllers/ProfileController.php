<?php

namespace api\modules\v1\controllers;

use Yii;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\CinemaWatchedForm;
use common\models\User;

class ProfileController extends ApiController
{
    /**
     * @brief Метод для создания записей о просмотренных кино пользователем
     * @return ApiResponse|ApiResponseException
     */
    public function actionCinemaWatched(): ApiResponse|ApiResponseException
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