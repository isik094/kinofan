<?php

namespace api\modules\v1\traits;

use api\components\ApiFunction;

trait ProductData
{
    /**
     * @brief Product Data
     * @return string[]
     */
    public function productData(): array
    {
        return [
            'id',
            'name',
            'image_url' => new ApiFunction('uploadsLink', ['image_url'], null, false, null),
            'site',
        ];
    }
}