<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait UserGenreCinemaTrait
{
    public function userGenreCinemaData(): array
    {
        return [
            'id' => new ApiGetter(['genre', 'id']),
            'name' => new ApiGetter(['genre', 'name']),
        ];
    }
}
