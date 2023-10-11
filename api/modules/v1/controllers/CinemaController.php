<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\CinemaSearch;
use api\modules\v1\traits\CinemaData;

/**
 * @OA\Tag(
 *     name="Cinema",
 *     description="Методы для кино"
 * )
 */
class CinemaController extends ApiWithSearchController
{
    use CinemaData;

    protected bool $isPrivate = false;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): CinemaSearch
    {
        return new CinemaSearch();
    }

    /**
     * @OA\Get (
     *     path="/cinema",
     *     summary="Получить список кино",
     *     description="Данный эндпоинт возвращает список кино",
     *     operationId="getListCinema",
     *     tags={"Cinema"},
     *      @OA\Parameter(name="page", in="query", description="Номер страницы для пагинации", required=false,
     *         @OA\Schema(type="integer", default="1"),
     *      ),
     *      @OA\Parameter(name="limit", in="query", description="Количество запрашиваемых данных", required=false,
     *          @OA\Schema(type="integer", default="20"),
     *       ),
     *      @OA\Parameter(name="sort", in="query", description="Название атрибута по которому нужна сортировка пример: id или -id", required=false,
     *          @OA\Schema(type="string", example="-id"),
     *       ),
     *      @OA\Parameter(name="id", in="query", description="ID кино", required=false,
     *          @OA\Schema(type="integer"),
     *       ),
     *      @OA\Parameter(name="year", in="query", description="Год кино", required=false,
     *          @OA\Schema(type="integer"),
     *       ),
     *      @OA\Parameter(name="type", in="query", description="Тип кино", required=false,
     *          @OA\Schema(type="string", default="movie", enum={"movie", "series", "cartoon", "anime", "tv_show"}),
     *       ),
     *      @OA\Parameter(name="genre_id", in="query", description="ID жанра", required=false,
     *          @OA\Schema(type="integer"),
     *       ),
     *      @OA\Parameter(name="country_id", in="query", description="ID страны", required=false,
     *          @OA\Schema(type="integer"),
     *       ),
     *     @OA\Response(response=200, description="Запрос выполнен успешно",
     *         @OA\JsonContent(ref="#/components/schemas/Cinema"),
     *     ),
     *     @OA\Response(response=500, description="Ошибка на стороне сервера",
     *         @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *     ),
     * )
     *
     * @brief Список кино
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->cinemaData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Get (
     *     path="/cinema/{id}",
     *     summary="Получить данные кино",
     *     description="Данный эндпоинт возвращает данные кино",
     *     operationId="getCinema",
     *     tags={"Cinema"},
     *      @OA\Parameter(name="id", in="path", description="ID кино", required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *     @OA\Response(response=200, description="Запрос выполнен успешно",
     *         @OA\JsonContent(ref="#/components/schemas/Cinema"),
     *     ),
     *     @OA\Response(response=406, description="Ошибка валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *     ),
     *      @OA\Response(response=404, description="Не найдено",
     *          @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *      ),
     *     @OA\Response(response=500, description="Ошибка на стороне сервера",
     *         @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *     ),
     * )
     *
     * @brief Получить фильм
     * @param int $id
     * @return ApiResponse|ApiResponseException
     */
    public function actionView(int $id): ApiResponse|ApiResponseException
    {
        try {
            return new ApiResponse(false, $this->makeObject($this->findCinema($id), $this->cinemaData()));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}
