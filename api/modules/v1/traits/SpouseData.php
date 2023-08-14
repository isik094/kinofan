<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;
use api\modules\v1\traits\duplicate\PersonDataDuplicate;

trait SpouseData
{
    use PersonDataDuplicate;

    /**
     * @brief Spouse Data
     * @return string[]
     */
    public function spouseData()
    {
        return [
            'id',
            'spouse' => new ApiGetter('spouse', $this->personDataDuplicate()),
            'divorced',
            'divorced_reason',
            'children',
            'relation',
        ];
    }
}