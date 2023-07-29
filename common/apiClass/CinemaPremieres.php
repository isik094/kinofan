<?php

namespace common\apiClass;

use common\models\Cinema;
use common\models\ErrorLog;
use GuzzleHttp\Exception\GuzzleException;

class CinemaPremieres extends KinopoiskApiUnofficial
{
    use Month;

    /**
     * @brief Премьеры фильмов
     * @return bool
     * @throws GuzzleException
     */
    public function start(): bool
    {
        try {
            $currentYear = (int)date('Y', time());
            for ($i = $currentYear; $i <= $currentYear + 5; $i++) {
                foreach ($this->getMonth() as $month) {
                    if ($premieres = $this->getFilmPremieres($i, $month)) {
                        foreach ($premieres->items as $item) {
                            $premiereRu = $item->premiereRu ? strtotime($item->premiereRu) : null;
                            if (!Cinema::findOne(['id_kp' => $item->kinopoiskId]) && $premiereRu >= time()) {
                                $cinema = new Cinema();
                                $cinema->id_kp = $item->kinopoiskId;
                                $cinema->name_ru = $item->nameRu;
                                $cinema->name_original = $item->nameEn;
                                $cinema->year = $item->year;
                                $cinema->poster_url = self::uploadCinemaFile($item->posterUrl, $item->kinopoiskId);
                                $cinema->poster_url_preview = self::uploadCinemaFile($item->posterUrlPreview, $item->kinopoiskId);
                                $cinema->film_length = $item->duration;
                                $cinema->premiere_ru = $premiereRu;
                                $cinema->saveStrict();

                                $cinemaData = new CinemaData($item->kinopoiskId);
                                $cinemaData->createCountryCinema($item, $cinema);
                                $cinemaData->createGenreCinema($item, $cinema);

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