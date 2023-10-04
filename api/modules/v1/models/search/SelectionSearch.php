<?php

namespace api\modules\v1\models\search;

use yii\db\ActiveQuery;
use common\models\Selection;
use api\models\SearchAttributeRules;

class SelectionSearch extends Selection
{
    /**
     * @inheritDoc
     * @return array
     */
    public function rules(): array
    {
        return [
            ['id', 'integer'],
            ['name', 'string'],
        ];
    }

    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes(): array
    {
        return [
            'id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'name' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING),
        ];
    }

    /**
     * @return array
     */
    public function sortAttributes(): array
    {
        return [
            'id',
            'name',
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
        $query = Selection::find();

        $query->andFilterWhere(['and',
            ['id' => $this->id],
            ['like', 'name', $this->name],
        ]);

        return $query;
    }
}