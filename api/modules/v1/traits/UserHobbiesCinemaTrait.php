<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait UserHobbiesCinemaTrait
{
    public function userHobbiesCinemaData(): array
    {
        return [
            'id' => new ApiGetter(['hobbies', 'id']),
            'name' => new ApiGetter(['hobbies', 'name']),
        ];
    }
}