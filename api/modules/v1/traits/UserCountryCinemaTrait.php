<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait UserCountryCinemaTrait
{
    public function userCountryCinemaData(): array
    {
        return [
            'id' => new ApiGetter(['country', 'id']),
            'name' => new ApiGetter(['country', 'name']),
        ];
    }
}