<?php

namespace api\modules\v1\models\search;

use common\models\CinemaCountry;
use common\models\CinemaGenre;
use yii\db\ActiveQuery;
use common\models\Cinema;
use api\models\SearchAttributeRules;

class CinemaSearch extends Cinema
{
    /**
     * @var string
     */
    public $fromDateCreate;
    /**
     * @var string
     */
    public $toDateCreate;
    /**
     * @var string
     */
    public $genres;
    /**
     * @var string
     */
    public $countries;

    /**
     * @inheritDoc
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'year'], 'integer'],
            [['type', 'genres', 'countries'], 'string'],
            [['fromDateCreate', 'toDateCreate', 'amount'], 'safe'],
        ];
    }

    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes(): array
    {
        return [
            'id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'year' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'type' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING),
            'genres' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING),
            'countries' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING),
            'fromDateCreate' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING, ['format' => 'dd.mm.yyyy', 'example' => '22.12.1991']),
            'toDateCreate' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING, ['format' => 'dd.mm.yyyy', 'example' => '22.12.1991']),
        ];
    }

    /**
     * @return array
     */
    public function sortAttributes(): array
    {
        return [
            'id' => self::tableName() . '.id',
            'type' => self::tableName() . '.type',
            'year' => self::tableName() . '.year',
            'genres' => CinemaGenre::tableName() . '.genre_id',
            'countries' => CinemaCountry::tableName() . '.country_id',
            'createdAt' => self::tableName() . '.created_at',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveQuery
     * @throws \Exception
     */
    public function search(array $params): ActiveQuery
    {
        $this->loadSearchParams($params);
        $this->validateSearch();
        $query = Cinema::find()
            ->joinWith('cinemaGenres')
            ->joinWith('cinemaCountries');

        $query->andFilterWhere([
            self::tableName() . '.id' => $this->id,
            self::tableName() . '.type' => $this->type,
            self::tableName() . '.year' => $this->year,
            CinemaGenre::tableName() . '.genre_id' => $this->splitStrToArray($this->genres),
            CinemaCountry::tableName() . '.country_id' => $this->splitStrToArray($this->countries),
        ]);

        if ($this->fromDateCreate && $this->toDateCreate) {
            $query->andWhere([
                'between',
                'created_at',
                strtotime($this->fromDateCreate),
                strtotime($this->toDateCreate . '+1 days')
            ]);
        }

        return $query;
    }
}