<?php

namespace common\apiClass;

use common\models\Cinema;
use common\models\ErrorLog;
use GuzzleHttp\Exception\GuzzleException;

class CinemaReleases extends KinopoiskApiUnofficial
{
    use Month;

    /**
     * @brief Релизы фильмов
     * @return bool
     * @throws GuzzleException
     */
    public function start(): bool
    {
        try {
            $currentYear = (int)date('Y', time());
            for ($i = $currentYear; $i <= $currentYear + 4; $i++) {
                foreach ($this->getMonth() as $month) {
                    $premieres = $this->getFilmReleases($i, $month);
                    if ($premieres?->total > 0 && count($premieres?->releases)) {
                        $total = ceil($premieres->total / count($premieres->releases));
                        for ($a = 1; $a <= $total; $a++) {
                            if ($premieres = $this->getFilmReleases($i, $month, $a)) {
                                foreach ($premieres->releases as $item) {
                                    $releaseDate = $item->releaseDate ? strtotime($item->releaseDate) : null;
                                    if (!Cinema::findOne(['id_kp' => $item->filmId]) && $releaseDate >= time()) {
                                        $cinema = new Cinema();
                                        $cinema->id_kp = $item->filmId;
                                        $cinema->name_ru = $item->nameRu;
                                        $cinema->name_original = $item->nameEn;
                                        $cinema->year = $item->year;
                                        $cinema->poster_url = self::uploadCinemaFile($item->posterUrl, $item->filmId);
                                        $cinema->poster_url_preview = self::uploadCinemaFile($item->posterUrlPreview, $item->filmId);
                                        $cinema->film_length = $item->duration;
                                        $cinema->release_date = $releaseDate;
                                        $cinema->saveStrict();

                                        $cinemaData = new CinemaData($item->filmId);
                                        $cinemaData->createCountryCinema($item, $cinema);
                                        $cinemaData->createGenreCinema($item, $cinema);

                                    }
                                }
                            }
                        }
                    }
                }
            }
            return true;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}