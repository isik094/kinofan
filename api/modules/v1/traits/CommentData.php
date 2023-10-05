<?php

namespace api\modules\v1\traits;

use api\components\ApiGetter;

trait CommentData
{
    use UserData;

    /**
     * @brief Comment Data
     * @return string[]
     */
    public function commentData(): array
    {
        return [
            'id',
            'text',
            'created_at',
            'user' => new ApiGetter('user', $this->userData()),
        ];
    }
}