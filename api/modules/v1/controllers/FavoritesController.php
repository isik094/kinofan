<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\components\ApiDataPagination;
use api\modules\v1\traits\CinemaData;
use api\modules\v1\traits\FavoritesData;
use common\models\User;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\AddFavoritesForm;
use api\modules\v1\models\search\FavoritesSearch;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="Методы для раздела избранные"
 * )
 */
class FavoritesController extends ApiWithSearchController
{
    use FavoritesData;
    use CinemaData;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): FavoritesSearch
    {
        return new FavoritesSearch();
    }

    /**
     * @OA\Get (
     *        path="/favorites",
     *        summary="Получить список избранных для пользователя",
     *        description="Данный эндпоинт возвращает список избранных кино для пользователя",
     *        operationId="getListFavorites",
     *        tags={"Favorites"},
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
     *         @OA\Parameter(name="user_id", in="query", description="ID пользователя", required=false,
     *             @OA\Schema(type="integer"),
     *          ),
     *         @OA\Response(response=200, description="Запрос выполнен успешно",
     *           @OA\JsonContent(
     *               allOf={
     *               @OA\Schema(@OA\Property(property="id", type="integer", example="1", description="ID избранного")),
     *               @OA\Schema(
     *                    @OA\Property(
     *                        property="user",
     *                        type="object",
     *                            @OA\Property(property="id", type="integer", example="1", description="ID пользователя"),
     *                            @OA\Property(property="username", type="email",example="qwe@gmail.com", description="Никнейм пользователя"),
     *                            @OA\Property(property="email", type="email", example="qwe@gmail.com", description="Электронная почта пользователя"),
     *                            @OA\Property(property="status", type="integer", example="10", description="Статус пользователя", enum={"0", "9", "10"}),
     *                            @OA\Property(property="statusText", type="string", example="Активен", description="Статус пользователя", enum={"Удален", "Не активен", "Активен"}),
     *                            @OA\Property(property="created_at", type="integer", example="1687464863", description="Время в unixtime"),
     *                    ),
     *               ),
     *               @OA\Schema(
     *                   @OA\Property(
     *                         property="cinema",
     *                         type="object",
     *                           @OA\Property(property="id", type="integer", example="1", description="ID кино"),
     *                           @OA\Property(property="id_kp", type="integer", example="1", description="ID кинопоиска"),
     *                           @OA\Property(property="name_ru", type="string", example="Матрица", description="Название кино на русском"),
     *                           @OA\Property(property="name_original", type="string", example="Matrix", description="Оригинальное название"),
     *                           @OA\Property(property="poster_url", type="string", example="https://kinofan.api/v1/file/rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на постер"),
     *                           @OA\Property(property="poster_url_preview", type="string", example="https://kinofan.api/v1/file/rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на превью"),
     *                           @OA\Property(property="rating_kinopoisk", type="float", example="8.2", description="Рейтинг кино на кинопоиске"),
     *                           @OA\Property(property="year", type="integer", example="2023", description="Год"),
     *                           @OA\Property(property="film_length", type="integer", example="120", description="Продолжительность"),
     *                           @OA\Property(property="slogan", type="string", example="Добро пожаловать в реальный мир", description="Слоган"),
     *                           @OA\Property(property="description", type="string", example="Жизнь Томаса Андерсона разделена на две части: днём он-самый обычный офисный работник, получающий нагоняи от начальства, а ночью превращается в хакера по имени Нео, и нет места в сети, куда он бы не смог проникнуть. Но однажды всё меняется. Томас узнаёт ужасающую правду о реальности.", description="Описание"),
     *                           @OA\Property(property="type", type="string", example="movie", description="Тип", enum={"movie", "series", "cartoon", "anime", "tv_show"}),
     *                           @OA\Property(property="rating_mpaa", type="string", example="r", description="Рейтинг MPAA"),
     *                           @OA\Property(property="rating_age_limits", type="string", example="age16", description="Возрастной лимит"),
     *                           @OA\Property(property="start_year", type="integer", example="1691150253", description="Старт"),
     *                           @OA\Property(property="end_year", type="integer", example="1691150253", description="Конец"),
     *                           @OA\Property(property="serial", type="integer", example="0", description="Сериал"),
     *                           @OA\Property(property="completed", type="integer", example="0", description="Окончен или нет (для сериалов)"),
     *                           @OA\Property(property="created_at", type="integer", example="1691150253", description="Время создания"),
     *                           @OA\Property(property="premiere_ru", type="integer", example="1691150253", description="Время премьеры в РФ"),
     *                           @OA\Property(property="release_date", type="integer", example="1691150253", description="Время цифрового релиза"),
     *                           @OA\Property(property="rating_imdb", type="float", example="8.2", description="Рейтинг IMDB"),
     *                 ),
     *               ),
     *             }
     *           ),
     *        ),
     *        @OA\Response(response=406, description="Ошибка валидации",
     *            @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *        ),
     *        @OA\Response(response=500, description="Ошибка на стороне сервера",
     *            @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *        ),
     *     security={{"bearerAuth":{}}},
     * )
     *
     * @brief Получить список избранных
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

            $data = $this->makeObjects($apiSearch->query->all(), $this->favoritesData());

            return new ApiResponse(false, new ApiDataPagination($data, $apiSearch->lastPage));
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *       path="/favorites",
     *       summary="Добавить кино в избранное",
     *       description="Данный эндпоинт добавляет кино в избранное для текущего пользователя",
     *       operationId="addFavorites",
     *       tags={"Favorites"},
     *       @OA\RequestBody(
     *         description = "Данные для добавления в избранное",
     *         required = true,
     *         @OA\JsonContent(ref="#/components/schemas/AddFavoritesForm"),
     *       ),
     *          @OA\Response(response=200, description="Запрос выполнен успешно",
     *            @OA\JsonContent(
     *                allOf={
     *                @OA\Schema(@OA\Property(property="id", type="integer", example="1", description="ID избранного")),
     *                @OA\Schema(
     *                     @OA\Property(
     *                         property="user",
     *                         type="object",
     *                             @OA\Property(property="id", type="integer", example="1", description="ID пользователя"),
     *                             @OA\Property(property="username", type="email",example="qwe@gmail.com", description="Никнейм пользователя"),
     *                             @OA\Property(property="email", type="email", example="qwe@gmail.com", description="Электронная почта пользователя"),
     *                             @OA\Property(property="status", type="integer", example="10", description="Статус пользователя", enum={"0", "9", "10"}),
     *                             @OA\Property(property="statusText", type="string", example="Активен", description="Статус пользователя", enum={"Удален", "Не активен", "Активен"}),
     *                             @OA\Property(property="created_at", type="integer", example="1687464863", description="Время в unixtime"),
     *                     ),
     *                ),
     *                @OA\Schema(
     *                    @OA\Property(
     *                          property="cinema",
     *                          type="object",
     *                            @OA\Property(property="id", type="integer", example="1", description="ID кино"),
     *                            @OA\Property(property="id_kp", type="integer", example="1", description="ID кинопоиска"),
     *                            @OA\Property(property="name_ru", type="string", example="Матрица", description="Название кино на русском"),
     *                            @OA\Property(property="name_original", type="string", example="Matrix", description="Оригинальное название"),
     *                            @OA\Property(property="poster_url", type="string", example="https://kinofan.api/v1/file/rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на постер"),
     *                            @OA\Property(property="poster_url_preview", type="string", example="https://kinofan.api/v1/file/rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на превью"),
     *                            @OA\Property(property="rating_kinopoisk", type="float", example="8.2", description="Рейтинг кино на кинопоиске"),
     *                            @OA\Property(property="year", type="integer", example="2023", description="Год"),
     *                            @OA\Property(property="film_length", type="integer", example="120", description="Продолжительность"),
     *                            @OA\Property(property="slogan", type="string", example="Добро пожаловать в реальный мир", description="Слоган"),
     *                            @OA\Property(property="description", type="string", example="Жизнь Томаса Андерсона разделена на две части: днём он-самый обычный офисный работник, получающий нагоняи от начальства, а ночью превращается в хакера по имени Нео, и нет места в сети, куда он бы не смог проникнуть. Но однажды всё меняется. Томас узнаёт ужасающую правду о реальности.", description="Описание"),
     *                            @OA\Property(property="type", type="string", example="movie", description="Тип", enum={"movie", "series", "cartoon", "anime", "tv_show"}),
     *                            @OA\Property(property="rating_mpaa", type="string", example="r", description="Рейтинг MPAA"),
     *                            @OA\Property(property="rating_age_limits", type="string", example="age16", description="Возрастной лимит"),
     *                            @OA\Property(property="start_year", type="integer", example="1691150253", description="Старт"),
     *                            @OA\Property(property="end_year", type="integer", example="1691150253", description="Конец"),
     *                            @OA\Property(property="serial", type="integer", example="0", description="Сериал"),
     *                            @OA\Property(property="completed", type="integer", example="0", description="Окончен или нет (для сериалов)"),
     *                            @OA\Property(property="created_at", type="integer", example="1691150253", description="Время создания"),
     *                            @OA\Property(property="premiere_ru", type="integer", example="1691150253", description="Время премьеры в РФ"),
     *                            @OA\Property(property="release_date", type="integer", example="1691150253", description="Время цифрового релиза"),
     *                            @OA\Property(property="rating_imdb", type="float", example="8.2", description="Рейтинг IMDB"),
     *                  ),
     *                ),
     *              }
     *            ),
     *         ),
     *        @OA\Response(response=406, description="Ошибка валидации",
     *            @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *        ),
     *       @OA\Response(
     *           response=500,
     *           description="Ошибка на стороне сервера",
     *           @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *       ),
     *     security={{"bearerAuth":{}}},
     *  )
     *
     * @brief Добавить в избранное
     * @return ApiResponse|ApiResponseException
     */
    public function actionAdd(): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $cinema = $this->findCinema(Yii::$app->request->post('cinema_id'));
            $model = new AddFavoritesForm(['user' => $user]);
            $model->cinema_id = $cinema->id;

            if ($favorites = $model->create()) {
                return new ApiResponse(false, $this->makeObject($favorites, $this->favoritesData()));
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Delete(
     *      path="/favorites/{id}",
     *      summary="Удалить из избранного кино",
     *      description="Данный эндпоинт удаляет кино из избранного у текущего пользователя",
     *      operationId="delete",
     *      tags={"Favorites"},
     *      @OA\Parameter(name="id", in="path", description="ID избранного", required=true,
     *          @OA\Schema(type="integer"),
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Запрос выполнен успешно",
     *          @OA\JsonContent(oneOf={@OA\Schema(
     *              @OA\Property(property="error", type="boolean", example="false", description="Булево обозначение ошибки"),
     *              @OA\Property(property="message", type="boolean", example="true", description="Текст сообщения ошибки на стороне сервера"),
     *              @OA\Property(property="status", type="integer", example="200", description="Код ошибки"),
     *          )}),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Пустой или неправильный токен",
     *          @OA\JsonContent(ref="#/components/schemas/Unauthorized"),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Запрещено",
     *          @OA\JsonContent(ref="#/components/schemas/Forbidden"),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Не найдено",
     *          @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка на стороне сервера",
     *          @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *      ),
     *     security={{"bearerAuth":{}}},
     * )
     *
     * @brief Удалить из избранных
     * @param int $id
     * @return ApiResponse|ApiResponseException
     * @throws \Throwable
     */
    public function actionDelete(int $id): ApiResponse|ApiResponseException
    {
        try {
            $model = $this->findFavorites($id);
            return new ApiResponse(false, (bool)$model->delete());
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}