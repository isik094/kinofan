<?php

namespace api\modules\v1\models\search;

use common\models\User;
use yii\db\ActiveQuery;
use api\models\SearchAttributeRules;
use common\models\CinemaWatched;

class CinemaWatchedSearch extends CinemaWatched
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
     * @param User $user
     *
     * @return ActiveQuery
     *
     * @throws \Exception
     */
    public function search(array $params, User $user): ActiveQuery
    {
        $this->loadSearchParams($params);
        $this->validateSearch();
        $query = CinemaWatched::find();

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id ?? $user->id,
            'cinema_id' => $this->cinema_id,
        ]);

        return $query;
    }
}