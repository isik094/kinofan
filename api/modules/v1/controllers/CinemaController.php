<?php

namespace api\modules\v1\controllers;

use Yii;
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
    protected bool $isPrivate = false;

    use CinemaData;

    /**
     * @inheritDoc
     */
    public function getSearchModel()
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
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка на стороне сервера",
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
     *     path="/cinema/id",
     *     summary="Получить данные кино",
     *     description="Данный эндпоинт возвращает данные кино",
     *     operationId="getCinema",
     *     tags={"Cinema"},
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка на стороне сервера",
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