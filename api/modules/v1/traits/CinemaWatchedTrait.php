<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait CinemaWatchedTrait
{
    use UserData;
    use CinemaData;

    public function cinemaWatchedData(): array
    {
        return [
            'user' => new ApiGetter('user', $this->userData()),
            'cinema' => new ApiGetter('cinema', $this->cinemaData()),
        ];
    }
}