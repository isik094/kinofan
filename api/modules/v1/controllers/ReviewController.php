<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use common\models\Comment;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\comment\CreateForm;
use api\modules\v1\traits\CinemaData;
use api\modules\v1\traits\CommentData;
use common\models\User;

/**
 * @OA\Tag(
 *     name="Review",
 *     description="Методы для отзывов"
 * )
 */
class ReviewController extends ApiController
{
    use CommentData;
    use CinemaData;

    /**
     * @OA\Post(
     *      path="/review",
     *      summary="Создать отзыв (комментарий) к кино",
     *      description="Данный эндпоинт создаeт новый отзыв (комментарий) или ответ к текущему отзыву (комментарию)",
     *      operationId="addReview",
     *      tags={"Review"},
     *      @OA\RequestBody(
     *        description = "Данные для создания отзыва",
     *        required = true,
     *        @OA\JsonContent(ref="#/components/schemas/CreateForm")
     *      ),
     *      @OA\Response(response=200, description="Запрос выполнен успешно",
     *          @OA\JsonContent(),
     *      ),
     *      @OA\Response(
     *          response=406,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка на стороне сервера",
     *          @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *      ),
     *      security={{"bearerAuth":{}}},
     * )
     *
     * @brief Создать комментарий
     * @return ApiResponse|ApiResponseException
     */
    public function actionCreate(): ApiResponse|ApiResponseException
    {
        try {
            $cinema = $this->findCinema(Yii::$app->request->post('cinema_id'));

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
