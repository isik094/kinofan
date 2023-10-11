<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\SimilarSearch;
use api\modules\v1\traits\SimilarData;

/**
 * @OA\Tag(
 *     name="Similar",
 *     description="Методы для получения похожих фильмов"
 * )
 */
class SimilarController extends ApiWithSearchController
{
    use SimilarData;

    protected bool $isPrivate = false;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): SimilarSearch
    {
        return new SimilarSearch();
    }

    /**
     * @OA\Get (
     *        path="/similar",
     *        summary="Получить список похожих фильмов",
     *        description="Данный эндпоинт возвращает список похожих фильмов",
     *        operationId="getListSimilar",
     *        tags={"Similar"},
     *         @OA\Parameter(name="page", in="query", description="Номер страницы для пагинации", required=false,
     *            @OA\Schema(type="integer", default="1"),
     *         ),
     *         @OA\Parameter(name="limit", in="query", description="Количество запрашиваемых данных", required=false,
     *             @OA\Schema(type="integer", default="20"),
     *          ),
     *         @OA\Parameter(name="sort", in="query", description="Название атрибута по которому нужна сортировка пример: id или -id", required=false,
     *             @OA\Schema(type="string", example="-id"),
     *          ),
     *         @OA\Parameter(name="id", in="query", description="ID просмотра", required=false,
     *             @OA\Schema(type="integer"),
     *          ),
     *         @OA\Parameter(name="cinema_id", in="query", description="ID кино", required=false,
     *             @OA\Schema(type="integer"),
     *          ),
     *        @OA\Response(response=200, description="Запрос выполнен успешно",
     *            @OA\JsonContent(),
     *        ),
     *        @OA\Response(response=406, description="Ошибка валидации",
     *            @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *        ),
     *        @OA\Response(response=500, description="Ошибка на стороне сервера",
     *            @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *        ),
     * )
     *
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