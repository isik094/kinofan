<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait SimilarData
{
    use CinemaData;

    /**
     * @brief Similar Data
     * @return array
     */
    public function similarData(): array
    {
        return [
            'id',
            'similar' => new ApiGetter('similar', $this->cinemaData())
        ];
    }
}