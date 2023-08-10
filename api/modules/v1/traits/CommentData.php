<?php

namespace api\modules\v1\traits;

trait CommentData
{
    /**
     * @brief Comment Data
     * @return string[]
     */
    public function commentData(): array
    {
        return [
            'id',
            'user_id',
            'parent_id',
            'text',
            'created_at',
            'status',
        ];
    }
}