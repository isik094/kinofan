<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\BestCinemaPerson;
use api\modules\v1\traits\CinemaData;
use api\modules\v1\traits\PersonCinemaData;
use api\components\ApiDataPagination;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\models\search\PersonCinemaSearch;

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
     * @brief Кино персоны с наивысшим рейтинг Кинопоиска
     * @param int $id
     * @return ApiResponse|ApiResponseException
     */
    public function actionBestCinema(int $id): ApiResponse|ApiResponseException
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
