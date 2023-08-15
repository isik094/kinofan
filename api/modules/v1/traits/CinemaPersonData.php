<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait CinemaPersonData
{
    use CinemaData;

    /**
     * @brief Cinema Person Data
     * @return string[]
     */
    public function cinemaPersonData(): array
    {
        return [
            'id',
            'profession_key',
            'cinema' => new ApiGetter('cinema', $this->cinemaData()),
        ];
    }
}