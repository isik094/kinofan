<?php

namespace api\modules\v1\traits;

trait HobbiesTrait
{
    public function hobbiesData(): array
    {
        return [
            'id',
            'name',
        ];
    }
}