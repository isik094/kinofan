<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\modules\v1\models\CommentList;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\CommentSearch;
use api\modules\v1\traits\CommentData;

/**
 * @OA\Tag(
 *     name="Comment",
 *     description="Методы для комментариев пользователей о кино"
 * )
 */
class CommentController extends ApiWithSearchController
{
    use CommentData;

    protected bool $isPrivate = false;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): CommentSearch
    {
        return new CommentSearch();
    }

    /**
     * @OA\Get (
     *       path="/comment",
     *       summary="Получить список комментариев (отзывов) о фильме",
     *       description="Данный эндпоинт возвращает список комментариев с ответами к нему о фильме",
     *       operationId="getListComment",
     *       tags={"Comment"},
     *        @OA\Parameter(name="page", in="query", description="Номер страницы для пагинации", required=false,
     *           @OA\Schema(type="integer", default="1"),
     *        ),
     *        @OA\Parameter(name="limit", in="query", description="Количество запрашиваемых данных", required=false,
     *            @OA\Schema(type="integer", default="20"),
     *         ),
     *        @OA\Parameter(name="sort", in="query", description="Название атрибута по которому нужна сортировка пример: id или -id", required=false,
     *            @OA\Schema(type="string", example="-id"),
     *         ),
     *        @OA\Parameter(name="id", in="query", description="ID просмотра", required=false,
     *            @OA\Schema(type="integer"),
     *         ),
     *        @OA\Parameter(name="cinema_id", in="query", description="ID кино", required=false,
     *            @OA\Schema(type="integer"),
     *         ),
     *       @OA\Response(response=200, description="Запрос выполнен успешно",
     *           @OA\JsonContent(ref="#/components/schemas/Comment"),
     *       ),
     *       @OA\Response(response=406, description="Ошибка валидации",
     *           @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *       ),
     *       @OA\Response(response=500, description="Ошибка на стороне сервера",
     *           @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *       ),
     * )
     *
     * @brief Получить список комментариев к фильму
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->commentData());

            $commentObjects = [];
            foreach ($data as $object) {
                $commentObjects[] = new CommentList($object);
            }

            return new ApiResponse(false, new ApiDataPagination($commentObjects, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}