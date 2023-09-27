<?php

namespace api\modules\v1\models\search;

use api\models\SearchAttributeRules;
use common\models\Favorites;
use yii\db\ActiveQuery;

class FavoritesSearch extends Favorites
{
    /**
     * @inheritDoc
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id', 'cinema_id'], 'integer'],
        ];
    }

    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes(): array
    {
        return [
            'id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'user_id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'cinema_id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
        ];
    }

    /**
     * @return array
     */
    public function sortAttributes(): array
    {
        return [
            'id',
            'user_id',
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
        $query = Favorites::find();

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'cinema_id' => $this->cinema_id,
        ]);

        return $query;
    }
}