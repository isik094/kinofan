<?php

namespace api\modules\v1\controllers;

use Yii;
use common\models\Comment;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\comment\CreateForm;
use api\modules\v1\traits\CinemaData;
use api\modules\v1\traits\CommentData;
use common\models\User;

class ReviewController extends ApiController
{
    use CommentData;
    use CinemaData;

    /**
     * @brief Создать комментарий
     * @param int $cinema_id
     * @return ApiResponse|ApiResponseException
     */
    public function actionCreate(int $cinema_id): ApiResponse|ApiResponseException
    {
        try {
            $cinema = $this->findCinema($cinema_id);

            $model = new CreateForm();
            $model->user = User::getCurrent();
            $model->cinema = $cinema;
            $model->parent_id = Yii::$app->request->post('parent_id') ?? Comment::DEFAULT_PARENT_ID;
            $model->text = Yii::$app->request->post('text');

            if ($data = $model->create()) {
                return new ApiResponse(
                    false,
                    $this->makeObject($data, $this->commentData())
                );
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}
