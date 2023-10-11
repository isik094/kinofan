<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\SelectionSearch;
use api\modules\v1\traits\SelectionTrait;

/**
 * @OA\Tag(
 *     name="Selection",
 *     description="Методы для подборок"
 * )
 */
class SelectionController extends ApiWithSearchController
{
    use SelectionTrait;

    protected bool $isPrivate = false;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): SelectionSearch
    {
        return new SelectionSearch();
    }

    /**
     * @OA\Get (
     *       path="/selection",
     *       summary="Получить список подборок",
     *       description="Данный эндпоинт возвращает список подборок",
     *       operationId="getListSelection",
     *       tags={"Selection"},
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
     *        @OA\Parameter(name="name", in="query", description="Название подборки", required=false,
     *            @OA\Schema(type="string"),
     *         ),
     *       @OA\Response(response=200, description="Запрос выполнен успешно",
     *           @OA\JsonContent(),
     *       ),
     *       @OA\Response(response=406, description="Ошибка валидации",
     *           @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *       ),
     *       @OA\Response(response=500, description="Ошибка на стороне сервера",
     *           @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *       ),
     * )
     *
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
     * @OA\Get (
     *      path="/selection/{id}",
     *      summary="Получить данные подборки",
     *      description="Данный эндпоинт возвращает данные подборки",
     *      operationId="getSelection",
     *      tags={"Selection"},
     *       @OA\Parameter(name="id", in="path", description="ID подборки", required=true,
     *          @OA\Schema(type="integer"),
     *       ),
     *      @OA\Response(response=200, description="Запрос выполнен успешно",
     *          @OA\JsonContent(),
     *      ),
     *       @OA\Response(response=404, description="Не найдено",
     *           @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *       ),
     * )
     *
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