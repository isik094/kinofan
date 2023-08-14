<?php

namespace api\modules\v1\traits;

trait PersonFactData
{
    /**
     * @brief Person Fact Data
     * @return string[]
     */
    public function personFactData(): array
    {
        return [
            'id',
            'text',
        ];
    }
}