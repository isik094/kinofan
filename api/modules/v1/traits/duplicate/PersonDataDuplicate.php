<?php

namespace api\modules\v1\traits\duplicate;

use api\components\ApiFunction;

trait PersonDataDuplicate
{
    /**
     * @brief Person Data
     * @return array
     */
    public function personDataDuplicate(): array
    {
        return [
            'id',
            'person_kp_id',
            'web_url',
            'name_ru',
            'name_en',
            'sex',
            'poster_url' => new ApiFunction('uploadsLink', ['poster_url'], null, false, null),
            'growth',
            'birthday',
            'age',
            'birthplace',
            'deathplace',
            'has_awards',
            'profession',
        ];
    }
}