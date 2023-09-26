<?php

namespace api\modules\v1\traits;

use api\components\ApiFunction;
use api\components\ApiGetter;

trait PersonData
{
    use SpouseData;
    use PersonFactData;
    use CinemaPersonData;

    /**
     * @brief Person Data
     * @return array
     */
    public function personData(): array
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
            'spouses' => new ApiGetter('spouses', $this->spouseData()),
            'facts' => new ApiGetter('personFacts', $this->personFactData()),
            'cinema' => new ApiGetter('cinemaPeople', $this->cinemaPersonData())
        ];
    }
}
