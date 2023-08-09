<?php

namespace api\modules\v1\models\search;

use common\models\Similar;
use yii\db\ActiveQuery;
use api\models\SearchAttributeRules;

class SimilarSearch extends \common\models\Similar
{
    /**
     * @inheritDoc
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'cinema_id'], 'integer'],
        ];
    }

    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes()
    {
        return [
            'id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'cinema_id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
        ];
    }

    /**
     * @return array
     */
    public function sortAttributes()
    {
        return [
            'id',
            'cinema_id',
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
        $query = Similar::find();

        $query->andFilterWhere([
            'id' => $this->id,
            'cinema_id' => $this->cinema_id,
        ]);

        return $query;
    }
}