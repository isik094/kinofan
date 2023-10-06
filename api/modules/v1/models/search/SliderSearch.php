<?php

namespace api\modules\v1\models\search;

use yii\db\ActiveQuery;
use common\models\Slider;
use api\models\SearchAttributeRules;

class SliderSearch extends Slider
{
    /**
     * @inheritDoc
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id',], 'integer'],
            [['title', 'description'], 'string'],
        ];
    }

    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes(): array
    {
        return [
            'id' => new SearchAttributeRules(SearchAttributeRules::TYPE_INTEGER),
            'title' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING),
            'description' => new SearchAttributeRules(SearchAttributeRules::TYPE_STRING),
        ];
    }

    /**
     * @return array
     */
    public function sortAttributes(): array
    {
        return [
            'id',
            'title',
            'description'
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
        $query = Slider::find();

        $query->andFilterWhere(['and',
            ['id' => $this->id],
            ['like', 'title', $this->title],
            ['like', 'description', $this->description],
        ]);

        return $query;
    }
}