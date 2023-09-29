<?php

namespace api\modules\v1\traits;

trait GenreTrait
{
    public function genreData(): array
    {
        return [
            'id',
            'name',
        ];
    }
}