<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\modules\v1\models\BestCinemaPerson;
use api\modules\v1\traits\CinemaData;
use api\modules\v1\traits\PersonCinemaData;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\PersonCinemaSearch;

/**
 * @OA\Tag(
 *     name="PersonCinema",
 *     description="Методы для получения кино персоны и его роли в этом кино"
 * )
 */
class PersonCinemaController extends ApiWithSearchController
{
    use PersonCinemaData;
    use CinemaData;

    protected bool $isPrivate = false;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): PersonCinemaSearch
    {
        return new PersonCinemaSearch();
    }

    /**
     * @OA\Get (
     *         path="/person-cinema",
     *         summary="Получить список ролей в кино персоны",
     *         description="Данный эндпоинт возвращает список ролей персоны в кино со всеми данными",
     *         operationId="getListPersonCinema",
     *         tags={"PersonCinema"},
     *          @OA\Parameter(name="page", in="query", description="Номер страницы для пагинации", required=false,
     *             @OA\Schema(type="integer", default="1"),
     *          ),
     *          @OA\Parameter(name="limit", in="query", description="Количество запрашиваемых данных", required=false,
     *              @OA\Schema(type="integer", default="20"),
     *           ),
     *          @OA\Parameter(name="sort", in="query", description="Название атрибута по которому нужна сортировка пример: id или -id", required=false,
     *              @OA\Schema(type="string", example="-id"),
     *           ),
     *          @OA\Parameter(name="id", in="query", description="ID просмотра", required=false,
     *              @OA\Schema(type="integer"),
     *           ),
     *          @OA\Parameter(name="cinema_id", in="query", description="ID кино", required=false,
     *              @OA\Schema(type="integer"),
     *           ),
     *          @OA\Response(response=200, description="Запрос выполнен успешно",
     *            @OA\JsonContent()
     *          ),
     *         @OA\Response(response=406, description="Ошибка валидации",
     *             @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *         ),
     *         @OA\Response(response=500, description="Ошибка на стороне сервера",
     *             @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *         ),
     * )
     *
     * @brief Получить список товаров
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->personCinemaData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Get (
     *      path="/person-cinema/{id}",
     *      summary="Получить данные о роли персоне в кино",
     *      description="Данный эндпоинт возвращает данные о роли персоны в кино",
     *      operationId="getPersonCinema",
     *      tags={"PersonCinema"},
     *       @OA\Parameter(name="id", in="path", description="ID персоны", required=true,
     *          @OA\Schema(type="integer"),
     *       ),
     *      @OA\Response(response=200, description="Запрос выполнен успешно",
     *          @OA\JsonContent(),
     *      ),
     *       @OA\Response(response=404, description="Не найдено",
     *           @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *       ),
     *  )
     *
     * @brief Получить информацию о персонаже
     * @param int $id
     * @return ApiResponse|ApiResponseException
     */
    public function actionView(int $id): ApiResponse|ApiResponseException
    {
        try {
            return new ApiResponse(
                false,
                $this->makeObject($this->findPerson($id), $this->personData())
            );
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Get (
     *       path="/person-cinema/best/{id}",
     *       summary="Получить 10 лучших фильмов с участием персоны",
     *       description="Данный эндпоинт возвращает данные о 10 лучших фильмов с участием персоны",
     *       operationId="getPersonBestCinema",
     *       tags={"PersonCinema"},
     *        @OA\Parameter(name="id", in="path", description="ID персоны", required=true,
     *           @OA\Schema(type="integer"),
     *        ),
     *       @OA\Response(response=200, description="Запрос выполнен успешно",
     *           @OA\JsonContent(),
     *       ),
     *        @OA\Response(response=404, description="Не найдено",
     *            @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *        ),
     *        @OA\Response(response=500, description="Ошибка на стороне сервера",
     *            @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *        ),
     *  )
     *
     * @brief Кино персоны с наивысшим рейтинг Кинопоиска
     * @param int $id
     * @return ApiResponse|ApiResponseException
     */
    public function actionBest(int $id): ApiResponse|ApiResponseException
    {
        try {
            $person = $this->findPerson($id);
            $bestCinemaPerson = new BestCinemaPerson($person);
            $bestCinemas = $bestCinemaPerson->run();

            return new ApiResponse(
                false,
                $this->makeObjects($bestCinemas, $this->cinemaData())
            );
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}
