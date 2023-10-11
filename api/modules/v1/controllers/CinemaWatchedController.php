<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\modules\v1\models\search\CinemaWatchedSearch;
use api\modules\v1\traits\CinemaWatchedTrait;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\CinemaWatchedForm;
use common\models\User;

/**
 * @OA\Tag(
 *     name="CinemaWatched",
 *     description="Методы для просмотренных кино для пользователя"
 * )
 */
class CinemaWatchedController extends ApiWithSearchController
{
    use CinemaWatchedTrait;

    /**
     * @inheritDoc
     */
    public function getSearchModel(): CinemaWatchedSearch
    {
        return new CinemaWatchedSearch();
    }

    /**
     * @OA\Get (
     *      path="/cinema-watched",
     *      summary="Получить список просмотренных кино пользователя",
     *      description="Данный эндпоинт возвращает список кино которые просмотрел пользователь",
     *      operationId="getListCinemaWatched",
     *      tags={"CinemaWatched"},
     *       @OA\Parameter(name="page", in="query", description="Номер страницы для пагинации", required=false,
     *          @OA\Schema(type="integer", default="1"),
     *       ),
     *       @OA\Parameter(name="limit", in="query", description="Количество запрашиваемых данных", required=false,
     *           @OA\Schema(type="integer", default="20"),
     *        ),
     *       @OA\Parameter(name="sort", in="query", description="Название атрибута по которому нужна сортировка пример: id или -id", required=false,
     *           @OA\Schema(type="string", example="-id"),
     *        ),
     *       @OA\Parameter(name="id", in="query", description="ID просмотра", required=false,
     *           @OA\Schema(type="integer"),
     *        ),
     *       @OA\Parameter(name="user_id", in="query", description="ID пользователя", required=false,
     *           @OA\Schema(type="integer"),
     *        ),
     *       @OA\Parameter(name="cinema_id", in="query", description="ID кино", required=false,
     *           @OA\Schema(type="integer"),
     *        ),
     *      @OA\Response(response=200, description="Запрос выполнен успешно",
     *          @OA\JsonContent(
     *              allOf={
     *              @OA\Schema(
     *                   @OA\Property(
     *                       property="user",
     *                       type="object",
     *                           @OA\Property(property="id", type="integer", example="1", description="ID пользователя"),
     *                           @OA\Property(property="username", type="email",example="qwe@gmail.com", description="Никнейм пользователя"),
     *                           @OA\Property(property="email", type="email", example="qwe@gmail.com", description="Электронная почта пользователя"),
     *                           @OA\Property(property="status", type="integer", example="10", description="Статус пользователя", enum={"0", "9", "10"}),
     *                           @OA\Property(property="statusText", type="string", example="Активен", description="Статус пользователя", enum={"Удален", "Не активен", "Активен"}),
     *                           @OA\Property(property="created_at", type="integer", example="1687464863", description="Время в unixtime"),
     *                   ),
     *              ),
     *              @OA\Schema(
     *                  @OA\Property(
     *                        property="cinema",
     *                        type="object",
     *                          @OA\Property(property="id", type="integer", example="1", description="ID кино"),
     *                          @OA\Property(property="id_kp", type="integer", example="1", description="ID кинопоиска"),
     *                          @OA\Property(property="name_ru", type="string", example="Матрица", description="Название кино на русском"),
     *                          @OA\Property(property="name_original", type="string", example="Matrix", description="Оригинальное название"),
     *                          @OA\Property(property="poster_url", type="string", example="https://kinofan.api/v1/file/rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на постер"),
     *                          @OA\Property(property="poster_url_preview", type="string", example="https://kinofan.api/v1/file/rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на превью"),
     *                          @OA\Property(property="rating_kinopoisk", type="float", example="8.2", description="Рейтинг кино на кинопоиске"),
     *                          @OA\Property(property="year", type="integer", example="2023", description="Год"),
     *                          @OA\Property(property="film_length", type="integer", example="120", description="Продолжительность"),
     *                          @OA\Property(property="slogan", type="string", example="Добро пожаловать в реальный мир", description="Слоган"),
     *                          @OA\Property(property="description", type="string", example="Жизнь Томаса Андерсона разделена на две части: днём он-самый обычный офисный работник, получающий нагоняи от начальства, а ночью превращается в хакера по имени Нео, и нет места в сети, куда он бы не смог проникнуть. Но однажды всё меняется. Томас узнаёт ужасающую правду о реальности.", description="Описание"),
     *                          @OA\Property(property="type", type="string", example="movie", description="Тип", enum={"movie", "series", "cartoon", "anime", "tv_show"}),
     *                          @OA\Property(property="rating_mpaa", type="string", example="r", description="Рейтинг MPAA"),
     *                          @OA\Property(property="rating_age_limits", type="string", example="age16", description="Возрастной лимит"),
     *                          @OA\Property(property="start_year", type="integer", example="1691150253", description="Старт"),
     *                          @OA\Property(property="end_year", type="integer", example="1691150253", description="Конец"),
     *                          @OA\Property(property="serial", type="integer", example="0", description="Сериал"),
     *                          @OA\Property(property="completed", type="integer", example="0", description="Окончен или нет (для сериалов)"),
     *                          @OA\Property(property="created_at", type="integer", example="1691150253", description="Время создания"),
     *                          @OA\Property(property="premiere_ru", type="integer", example="1691150253", description="Время премьеры в РФ"),
     *                          @OA\Property(property="release_date", type="integer", example="1691150253", description="Время цифрового релиза"),
     *                          @OA\Property(property="rating_imdb", type="float", example="8.2", description="Рейтинг IMDB"),
     *                ),
     *              ),
     *            }
     *          ),
     *      ),
     *      @OA\Response(response=406, description="Ошибка валидации",
     *          @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *      ),
     *      @OA\Response(response=500, description="Ошибка на стороне сервера",
     *          @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *      ),
     *     security={{"bearerAuth":{}}},
     * )
     *
     * @brief Список просмотренных
     * @param int|null $page
     * @param int $limit
     * @param string|null $sort
     * @return ApiResponse|ApiResponseException
     */
    public function actionIndex(int $page = null, int $limit = 20, string $sort = null): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $searchModel = $this->getSearchModel();
            $query = $searchModel->search(Yii::$app->request->get(), $user);

            $apiSearch = $this->searchAndSort($page, $limit, $sort, $query);

            $data = $this->makeObjects($apiSearch->query->all(), $this->cinemaWatchedData());

            return new ApiResponse(
                false,
                new ApiDataPagination($data, $apiSearch->lastPage)
            );
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *      path="/cinema-watched",
     *      summary="Создать запись о просмотре кино пользователем",
     *      description="Данный эндпоинт создает записи о просмотре пользователем фильмов",
     *      operationId="createCinemaWatched",
     *      tags={"CinemaWatched"},
     *      @OA\RequestBody(
     *        description = "Данные для сохранения записей о просмотре фильмов",
     *        required = true,
     *        @OA\JsonContent(ref="#/components/schemas/CinemaWatchedForm"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Запрос выполнен успешно",
     *          @OA\JsonContent(ref="#/components/schemas/SuccessResponse"),
     *      ),
     *       @OA\Response(response=406, description="Ошибка валидации",
     *           @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *       ),
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка на стороне сервера",
     *          @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *      ),
     *     security={{"bearerAuth":{}}},
     *  )
     *
     * @brief Метод для создания записей о просмотренных кино пользователем
     * @return ApiResponse|ApiResponseException
     */
    public function actionCreate(): ApiResponse|ApiResponseException
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