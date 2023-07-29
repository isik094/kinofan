<?php

namespace common\apiClass;

use Yii;
use common\models\CinemaPerson;
use common\models\PersonCinema;
use common\models\PersonFact;
use common\models\Spouse;
use common\models\Image;
use common\models\Award;
use common\models\Video;
use common\models\Genre;
use common\models\Cinema;
use common\models\Person;
use common\models\Season;
use common\models\Similar;
use common\models\Company;
use common\models\Country;
use common\models\ErrorLog;
use common\models\CinemaFacts;
use common\models\CinemaGenre;
use common\models\AwardPerson;
use common\models\Distribution;
use common\models\CinemaCountry;
use common\models\CinemaBoxOffice;
use common\models\SequelAndPrequel;
use GuzzleHttp\Exception\GuzzleException;

class CinemaData extends KinopoiskApiUnofficial
{
    public static array $imageType = [
        'STILL',
        'SHOOTING',
        'POSTER',
        'FAN_ART',
        'PROMO',
        'CONCEPT',
        'WALLPAPER',
        'COVER',
        'SCREENSHOT',
    ];

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
     * @brief Запустить процесс парсинга данных с API Kinoposikunofficial
     * @return bool
     * @throws GuzzleException
     */
    public function run(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $film = $this->getFilm($this->kp_id);
            if ($film && !Cinema::findOne(['id_kp' => $film->kinopoiskId])) {
                $cinema = new Cinema();
                $cinema->id_kp = $film->kinopoiskId;
                $cinema->name_ru = $film->nameRu;
                $cinema->name_original = $film->nameOriginal;
                $cinema->poster_url = self::uploadCinemaFile($film->posterUrl, $this->kp_id);
                $cinema->poster_url_preview = self::uploadCinemaFile($film->posterUrlPreview, $this->kp_id);
                $cinema->rating_kinopoisk = $film->ratingKinopoisk;
                $cinema->year = $film->year;
                $cinema->film_length = $film->filmLength;
                $cinema->slogan = $film->slogan;
                $cinema->description = $film->description;
                $cinema->type = $film->type;
                $cinema->rating_mpaa = $film->ratingMpaa;
                $cinema->rating_age_limits = $film->ratingAgeLimits;
                $cinema->start_year = $film->startYear;
                $cinema->end_year = $film->endYear;
                $cinema->serial = (int)$film->serial;
                $cinema->completed = (int)$film->completed;
                $cinema->created_at = time();
                $cinema->updated_at = time();
                $cinema->rating_imdb = $film->ratingImdb;
                $cinema->saveStrict();

                $this->createPerson($cinema);
                $this->createSequelAndPrequel($cinema);
                $this->createFilmImage($cinema);
                $this->createFilmSimilars($cinema);
                $this->createFilmSeasons($cinema);
                $this->createDistributionCinema($cinema);
                $this->createCountryCinema($film, $cinema);
                $this->createGenreCinema($film, $cinema);
                $this->createFactsCinema($cinema);
                $this->createCinemaBoxOffice($cinema);
                $this->createCinemaAward($cinema);
                $this->createCinemaVideo($cinema);

                $transaction->commit();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $transaction->rollBack();
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать персону и все его данные записать в бд
     * @param Cinema $cinema
     * @return false
     * @throws GuzzleException
     */
    public function createPerson(Cinema $cinema)
    {
        try {
            if ($personsCinema = $this->getStaff($this->kp_id)) {
                foreach ($personsCinema as $item) {
                    $person = $this->getStaffPerson($item['staffId']);

                    $personModel = new Person();
                    $personModel->person_kp_id = $person->personId;
                    $personModel->web_url = $person->webUrl;
                    $personModel->name_ru = $person->nameRu;
                    $personModel->name_en = $person->nameEn;
                    $personModel->sex = $person->sex;
                    $personModel->poster_url = self::uploadPersonFile($person->posterUrl, $this->kp_id);
                    $personModel->growth = $person->growth;
                    $personModel->birthday = $person->birthday ? strtotime($person->birthday) : null;
                    $personModel->death = $person->death ? strtotime($person->death) : null;
                    $personModel->age = $person->age;
                    $personModel->birthplace = $person->birthplace;
                    $personModel->deathplace = $person->deathplace;
                    $personModel->has_awards = $person->hasAwards;
                    $personModel->profession = $person->profession;
                    $personModel->saveStrict();

                    $newPersonCinemaModel = new PersonCinema();
                    $newPersonCinemaModel->person_id = $personModel->id;
                    $newPersonCinemaModel->cinema_id = $cinema->id;
                    $newPersonCinemaModel->profession_text = $item->professionText;
                    $newPersonCinemaModel->saveStrict();

                    //супруги
                    foreach ($person->spouses as $spouse) {
                        if ($person = Person::findOne(['person_kp_id' => $spouse->personId])) {
                            $personId = $person->id;
                        } else {
                            $newPersonModel = new Person();
                            $newPersonModel->person_kp_id = $spouse->personId;
                            $newPersonModel->saveStrict();

                            $personId = $newPersonModel->id;
                        }

                        $spouseModel = new Spouse();
                        $spouseModel->person_id = $personModel->id;
                        $spouseModel->spouse_id = $personId;
                        $spouseModel->divorced = $spouse->divorced;
                        $spouseModel->divorced_reason = $spouse->divorcedReason;
                        $spouseModel->children = $spouse->children;
                        $spouseModel->relation = $spouse->relation;
                        $spouseModel->saveStrict();

                    }

                    //факты
                    foreach ($person->facts as $fact) {
                        $personFact = new PersonFact();
                        $personFact->person_id = $personModel->id;
                        $personFact->text = $fact;
                        $personFact->saveStrict();
                    }

                    //фильмы
                    foreach ($person->films as $film) {
                        if ($cinema = Cinema::findOne(['id_kp' => $film->filmId])) {
                            $cinemaId = $cinema->id;
                        } else {
                            $newCinemaModel = new Cinema();
                            $newCinemaModel->id_kp = $film->filmId;
                            $newCinemaModel->name_ru = $film->nameRu;
                            $newCinemaModel->name_original = $film->nameEn;
                            $newCinemaModel->rating_kinopoisk = $film->rating;
                            $newCinemaModel->description = $film->description;
                            $newCinemaModel->saveStrict();

                            $cinemaId = $newCinemaModel->id;
                        }

                        $cinemaPerson = new CinemaPerson();
                        $cinemaPerson->person_id = $personModel->id;
                        $cinemaPerson->cinema_id = $cinemaId;
                        $cinemaPerson->profession_key = $film->professionKey;
                        $cinemaPerson->saveStrict();
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Сиквел и прикевелы для фильмов
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createSequelAndPrequel(Cinema $cinema): bool
    {
        try {
            if ($sequelAndPrequels = $this->getFilmSequelsAndPrequels($this->kp_id)) {
                foreach ($sequelAndPrequels as $item) {
                    $sequelAndPrequelModel = new SequelAndPrequel();
                    $sequelAndPrequelModel->cinema_id = $cinema->id;
                    $sequelAndPrequelModel->relation_type = $item->relationType;
                    $sequelAndPrequelModel->id_kp = $item->filmId;
                    $sequelAndPrequelModel->saveStrict();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Сохранить картинки к фильму
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createFilmImage(Cinema $cinema): bool
    {
        try {
            if ($images = $this->getFilmImages($this->kp_id)) {
                foreach (self::$imageType as $item) {
                    for ($i = 1; $i <= $images->totalPages; $i++) {
                        if ($images = $this->getFilmImages($this->kp_id, $item, $i)) {
                            foreach ($images->items as $value) {
                                $imageModel = new Image();
                                $imageModel->cinema_id = $cinema->id;
                                $imageModel->image_url = $value->imageUrl;
                                $imageModel->preview_url = $value->previewUrl;
                                $imageModel->type = $item;
                                $imageModel->saveStrict();
                            }
                        }
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать записи с похожими фильмами
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createFilmSimilars(Cinema $cinema): bool
    {
        try {
            if ($similars = $this->getFilmSimilar($this->kp_id)) {
                foreach ($similars->items as $item) {
                    $similarModel = new Similar();
                    $similarModel->cinema_id = $cinema->id;
                    $similarModel->id_kp = $item->filmId;
                    $similarModel->saveStrict();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Сезоны сериалов
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createFilmSeasons(Cinema $cinema): bool
    {
        try {
            if ($seasons = $this->getFilmSeason($this->kp_id)) {
                foreach ($seasons->items as $season) {
                    foreach ($season->episodes as $episode) {
                        $seasonModel = new Season();
                        $seasonModel->cinema_id = $cinema->id;
                        $seasonModel->season_number = $episode->seasonNumber;
                        $seasonModel->episode_number = $episode->episodeNumber;
                        $seasonModel->name_ru = $episode->nameRu;
                        $seasonModel->name_en = $episode->nameEn;
                        $seasonModel->synopsis = $episode->synopsis;
                        $seasonModel->release_date = $episode->releaseDate ? strtotime($episode->releaseDate) : null;
                        $seasonModel->saveStrict();
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return true;
        }
    }

    /**
     * @brief Сохранить видео для фильмов
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createCinemaVideo(Cinema $cinema): bool
    {
        try {
            if ($videos = $this->getFilmVideos($this->kp_id)) {
                foreach ($videos->items as $video) {
                    $videoModel = new Video();
                    $videoModel->cinema_id = $cinema->id;
                    $videoModel->url = $video->url;
                    $videoModel->name = $video->name;
                    $videoModel->site = $video->site;
                    $videoModel->saveStrict();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать персону и записать участие в фильме
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createCinemaAward(Cinema $cinema): bool
    {
        try {
            if ($filmAwards = $this->getFilmAwards($this->kp_id)) {
                foreach ($filmAwards->items as $item) {
                    $award = new Award();
                    $award->cinema_id = $cinema->id;
                    $award->name = $item->name;
                    $award->win = (int)$item->win;
                    $award->image_url = self::uploadCinemaFile($item->imageUrl, $this->kp_id);
                    $award->nomination_name = $item->nominationName;
                    $award->year = $item->year;
                    $award->saveStrict();

                    foreach ($item->persons as $person) {
                        $awardPerson = new AwardPerson();
                        $awardPerson->award_id = $award->id;
                        $awardPerson->age = $person->age;
                        $awardPerson->profession = $person->profession;

                        if ($personModel = Person::findOne(['person_kp_id' => $person->kinopoiskId])) {
                            $awardPerson->person_id = $personModel->id;
                        } else {
                            $newPersonModel = new Person();
                            $newPersonModel->person_kp_id = $person->kinopoiskId;
                            $newPersonModel->web_url = $person->webUrl;
                            $newPersonModel->name_ru = $person->nameRu;
                            $newPersonModel->name_en = $person->nameEn;
                            $newPersonModel->sex = $person->sex;
                            $newPersonModel->poster_url = self::uploadPersonFile($person->posterUrl, $person->kinopoiskId);
                            $newPersonModel->growth = $person->growth;
                            $newPersonModel->birthday = $person->birthday ? strtotime($person->birthday) : null;
                            $newPersonModel->death = $person->death ? strtotime($person->death) : null;
                            $newPersonModel->saveStrict();

                            $awardPerson->person_id = $newPersonModel->id;
                        }
                        $awardPerson->saveStrict();
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать запись о бюджете и сборе фильма
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createCinemaBoxOffice(Cinema $cinema): bool
    {
        try {
            if ($boxOffice = $this->getFilmBoxOffice($this->kp_id)) {
                foreach ($boxOffice->items as $item) {
                    $cinemaBoxOffice = new CinemaBoxOffice();
                    $cinemaBoxOffice->cinema_id = $cinema->id;
                    $cinemaBoxOffice->type = $item->type;
                    $cinemaBoxOffice->amount = $item->amount;
                    $cinemaBoxOffice->symbol = $item->symbol;
                    $cinemaBoxOffice->saveStrict();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать запись о прокате в разных странах
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createDistributionCinema(Cinema $cinema): bool
    {
        try {
            if ($distribution = $this->getFilmDistributions($this->kp_id)) {
                foreach ($distribution->items as $item) {
                    $distributionModel = new Distribution();
                    $distributionModel->cinema_id = $cinema->id;
                    $distributionModel->type = $item->type;
                    $distributionModel->sub_type = $item->subType;
                    $distributionModel->date = strtotime($item->date);
                    $distributionModel->re_release = (int)$item->reRelease;

                    if ($countryName = $item?->country?->country) {
                        if ($existingCountryModel = Country::findOne(['name' => $countryName])) {
                            $distributionModel->country_id = $existingCountryModel->id;
                        } else {
                            $newCountryModel = Country::create($countryName);
                            $distributionModel->country_id = $newCountryModel->id;
                        }
                    }

                    foreach ($item->companies as $company) {
                        $companyName = trim($company->name);

                        if ($existingCompanyModel = Company::findOne(['name' => $companyName])) {
                            $distributionModel->company_id = $existingCompanyModel->id;
                        } else {
                            $newCompanyModel = Company::create($companyName);
                            $distributionModel->country_id = $newCompanyModel->id;
                        }
                    }
                    $distributionModel->saveStrict();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Сохранить факты фильма
     * @param Cinema $cinema
     * @return bool
     * @throws GuzzleException
     */
    public function createFactsCinema(Cinema $cinema): bool
    {
        try {
            if ($cinemaFacts = $this->getFilmFact($this->kp_id)) {
                foreach ($cinemaFacts->items as $item) {
                    $cinemaFactsModel = new CinemaFacts();
                    $cinemaFactsModel->cinema_id = $cinema->id;
                    $cinemaFactsModel->text = strip_tags($item->text);
                    $cinemaFactsModel->type = strtolower($item->type);
                    $cinemaFactsModel->spoiler = (int)$item->spoiler;
                    $cinemaFactsModel->saveStrict();
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать страну и создать запись фильма с этой страной
     * @param \stdClass $film
     * @param Cinema $cinema
     * @return bool
     */
    public function createCountryCinema(\stdClass $film, Cinema $cinema): bool
    {
        try {
            if ($countries = $film->countries) {
                foreach ($countries as $country) {
                    $countryName = trim($country->country);

                    if ($existingCountryModel = Country::findOne(['name' => $countryName])) {
                        $countryModel = $existingCountryModel;
                    } else {
                        $newCountryModel = Country::create($countryName);
                        $countryModel = $newCountryModel;
                    }
                    CinemaCountry::create($cinema, $countryModel);
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }

    /**
     * @brief Создать жанры и создать запись фильма с этим жанром
     * @param \stdClass $film
     * @param Cinema $cinema
     * @return bool
     */
    public function createGenreCinema(\stdClass $film, Cinema $cinema): bool
    {
        try {
            if ($genres = $film->genres) {
                foreach ($genres as $genre) {
                    $genreName = trim($genre->genre);
                    if ($genreName !== 'мультфильм') {
                        if ($existingGenreModel = Genre::findOne(['name' => $genreName])) {
                            $genreModel = $existingGenreModel;
                        } else {
                            $newGenreModel = Genre::create($genreName);
                            $genreModel = $newGenreModel;
                        }
                        CinemaGenre::create($cinema, $genreModel);
                    }
                }
                return true;
            }
            return false;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}