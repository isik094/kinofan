<?php

namespace api\modules\v1\traits;

trait CountryTrait
{
    public function countryData(): array
    {
        return [
            'id',
            'name',
        ];
    }
}