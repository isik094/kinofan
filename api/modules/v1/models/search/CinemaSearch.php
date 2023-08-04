<?php

namespace api\modules\v1\models\search;

use yii\db\ActiveQuery;
use common\models\Cinema;
use api\models\SearchAttributeRules;

class CinemaSearch extends \common\models\Cinema
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
     * @inheritDoc
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['fromDateCreate', 'toDateCreate', 'amount'], 'safe'],
        ];
    }

    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes()
    {
        return [
            'id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'fromDateCreate' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING, ['format' => 'dd.mm.yyyy', 'example' => '22.12.1991']),
            'toDateCreate' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING, ['format' => 'dd.mm.yyyy', 'example' => '22.12.1991']),
        ];
    }

    /**
     * @return array
     */
    public function sortAttributes()
    {
        return [
            'id',
            'createdAt' => 'created_at',
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
        $query = Cinema::find();

        $query->andFilterWhere([
            'id' => $this->id,
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