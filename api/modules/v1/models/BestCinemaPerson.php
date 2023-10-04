<?php

namespace api\modules\v1\models;

use common\models\Cinema;
use common\models\Person;

class BestCinemaPerson
{
    /**
     * @var Person
     */
    public Person $person;

    /**
     * @param Person $person
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @brief Получить фильмы актера с наивысшим рейтинг Кинопоиска
     * @return array|null
     */
    public function run(): ?array
    {
        return Cinema::find()
            ->joinWith('personCinemas')
            ->where(['person_cinema.person_id' => $this->person->id, 'cinema.deleted_at' => null])
            ->limit(10)
            ->orderBy(['cinema.rating_kinopoisk' => SORT_DESC])
            ->all();
    }
}