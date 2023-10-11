<?php

namespace api\modules\v1\models\search;

use common\models\Cinema;

class CinemaLiveSearch
{
    /**
     * @var string|null
     */
    public ?string $search;

    /**
     * @param string|null $search
     */
    public function __construct(?string $search)
    {
        $this->search = $search;
    }

    /**
     * @return array
     */
    public function search(): array
    {
        $query = Cinema::find()
            ->where(['deleted_at' => null]);

        $query->andFilterWhere(['or',
            ['like', 'name_ru', $this->search],
            ['like', 'name_original', $this->search],
        ]);

        $query->orderBy(['id' => SORT_DESC]);

        return $this->dataFormatting($query->all());
    }

    public function dataFormatting(?array $cinemas): array
    {
        $cinemaArray = [];
        foreach ($cinemas as $cinema) {
            $cinemaArray[] = [
            ];
        }

        return $cinemaArray;
    }
}