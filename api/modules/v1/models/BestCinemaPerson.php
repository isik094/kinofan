<?php

namespace api\modules\v1\models;

use common\models\Cinema;
use common\models\ErrorLog;
use common\models\User;

class BestCinemaPerson
{
    /**
     * @var User
     */
    public User $user;

    /**
     * @var int
     */
    public int $person_id;

    /**
     * @param User $user
     * @param int $person_id
     */
    public function __construct(User $user, int $person_id)
    {
        $this->user = $user;
        $this->person_id = $person_id;
    }

    /**
     * @brief Получить фильмы актера с наивысшим рейтинг Кинопоиска
     * @return array|null
     */
    public function run(): ?array
    {
        try {
            return Cinema::find()
                ->joinWith('personCinemas')
                ->where(['person_cinema.person_id' => $this->person_id, 'cinema.deleted_at' => null])
                ->limit(10)
                ->orderBy(['cinema.rating_kinopoisk' => SORT_DESC])
                ->all();
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }

        return null;
    }
}