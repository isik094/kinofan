<?php
namespace api\modules\v1\controllers;


use api\components\ApiResponse;
use api\components\ApiSearch;
use common\base\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;
use yii\web\BadRequestHttpException;

abstract class ApiWithSearchController extends ApiController
{
    /**
     * @return ActiveRecord
     */
    abstract public function getSearchModel();

    /**
     * @brief Возвращает аттрибуты, которые по которым можно осуществлять поиск
     * @return ApiResponse
     * @throws \Exception
     */
    public function actionGetSearchAttributes()
    {
        $searchModel = $this->getSearchModel();
        /**
         * @var ActiveRecord $searchModel
         */

        $result = [];
        foreach ($searchModel->searchAttributes() as $attribute => $rules) {
            if ($searchModel->isAttributeSafe($attribute)) {
                $result[$attribute] = $rules;
            }
        }

        return new ApiResponse(false, $result);
    }

    /**
     * @brief Вернет поля, по которым можно сортировать
     * @return ApiResponse
     * @throws \Exception
     */
    public function actionGetSortAttributes()
    {
        $result = [];
        foreach ($this->getSearchModel()->sortAttributes() as $key => $value) {
            if (is_string($key)) {
                $result[] = $key;
            } else {
                $result[] = $value;
            }
        }

        return new ApiResponse(false, $result);
    }

    /**
     * @param int|null $page
     * @param int $limit
     * @param string $sort
     * @param ActiveQuery $query
     * @return ApiSearch
     * @throws BadRequestHttpException
     */
    public function searchAndSort($page, $limit, $sort, ActiveQuery $query)
    {
        if (!$page || $page < 1) {
            $page = 1;
        }

        $limit = (int)$limit;
        $page = (int)$page;

        if ($sort) {
            $this->setSort($query, $sort);
        }

        $query->addOrderBy(['id' => SORT_DESC]);

        $lastPage = null;
        if ($page) {
            $count = $query->count();
            $query->offset(($page - 1) * $limit)->limit($limit);
            if (is_int($count / $limit)) {
                $lastPage = $count / $limit;
            } else {
                $lastPage = floor($count / $limit) + 1;
            }
        }

        return new ApiSearch($query, $lastPage);
    }

    /**
     * @brief Добавление сортировки к запросу
     * @param ActiveQuery $query
     * @param $sort
     * @throws BadRequestHttpException
     */
    protected function setSort(ActiveQuery $query, $sort)
    {
        $searchAttributes = $this->getSearchModel()->sortAttributes();
        $sortType = substr($sort, 0, 1) === '-' ? SORT_DESC : SORT_ASC;
        $param = str_replace('-', '', $sort);

        if (in_array($param, $searchAttributes)) {
            $query->addOrderBy([$param => $sortType]);
        } elseif (array_key_exists($param, $searchAttributes)) {
            $query->addOrderBy([$searchAttributes[$param] => $sortType]);
        } else {
            throw new BadRequestHttpException('Sort param ' . $param . ' is not allowed');
        }
    }
}