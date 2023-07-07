<?php

namespace common\apiClass;

use common\models\ErrorLog;
use GuzzleHttp\Exception\GuzzleException;

class VideoData extends Kinopoiskapiunofficial
{
    /**
     * @brief ID видео из кинопоиска
     * @var int
     */
    public int $kp_id;

    /**
     * @param int $kp_id
     */
    public function __construct(int $kp_id)
    {
        $this->kp_id = $kp_id;
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function run()
    {
        try {
            $film = $this->getFilm($this->kp_id);
            $filmSeason = $this->getFilmSeason($this->kp_id);
            $filmFact = $this->getFilmFact($this->kp_id);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }
}