<?php

namespace api\modules\v1\traits;

use api\components\ApiFunction;

trait PersonData
{
    /**
     * @brief Person Data
     * @return array
     */
    public function personData()
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