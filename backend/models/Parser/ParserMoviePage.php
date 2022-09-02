<?php

namespace backend\models\Parser;

require_once __DIR__ . '/../../../vendor/electrolinux/phpquery/phpQuery/phpQuery.php';

use Yii;
use common\models\Movies;

class ParserMoviePage
{
    /**
     * @var string
     */
    public string $htmlPageMovie;

    /**
     * @var Movies
     */
    //public Movies $model;

    /**
     * @param $htmlPageMovie
     * @param //Movies $model
     */
    //public function __construct($htmlPageMovie, Movies $model)
    public function __construct($htmlPageMovie)

    {
        $this->htmlPageMovie = $htmlPageMovie;
        //$this->model = $model;
    }

    /**
     * @brief Сформировать данные фильма и сохранить
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        try {
            $data = [];
            $htmlDomMovies = \phpQuery::newDocument($this->htmlPageMovie);

            $data['title_ru'] = $this->getTitleRu($htmlDomMovies);
            $data['title_eng'] = $this->getTitleEng($htmlDomMovies);
            $data['short_description'] = $this->getShortDescription($htmlDomMovies);
            $data['path'] = $this->getImages($htmlDomMovies);
            $data['time'] = $this->getTime($htmlDomMovies);
            $data['description'] = $this->getDescription($htmlDomMovies);
            //$data['age'] = $this->getAge($htmlDomMovies);
            $data['rating_mpaa'] = $this->getRatingMpaa($htmlDomMovies);
            //$data['rating_mpaa_designation'] = $this->getRatingMpaaDescription($htmlDomMovies);
            $data['about_film'] = $this->getAboutFilm($htmlDomMovies);

            echo '<pre>';
            print_r($data); die;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить название фильма
     * @param $htmlDomMovies
     * @return string
     * @throws \Exception
     */
    public function getTitleRu($htmlDomMovies): string
    {
        try {
            $titleRu = $htmlDomMovies->find('div.styles_title__hTCAr h1.styles_title__65Zwx span');

            return trim(pq($titleRu)->text());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить оригинальное название фильма
     * @param $htmlDomMovies
     * @return string
     * @throws \Exception
     */
    public function getTitleEng($htmlDomMovies): string
    {
        try {
            $titleEng = $htmlDomMovies->find('div.styles_title__hTCAr div.styles_root__LIL2v span.styles_originalTitle__JaNKM');

            return trim(pq($titleEng)->text());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить краткое описание если оно есть
     * @param $htmlDomMovies
     * @return string|null
     * @throws \Exception
     */
    public function getShortDescription($htmlDomMovies): ?string
    {
        try {
            $shortDescription = $htmlDomMovies->find('div.styles_topText__p__5L p.styles_root__aZJRN');

            return trim(pq($shortDescription)->text());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить картинку фильма
     * @param $htmlDomMovies
     * @return string|null
     */
    public function getImages($htmlDomMovies): ?string
    {
        try {
            $imageSrcUrl = $htmlDomMovies->find('div.styles_root__0qoat a.styles_posterLink__C1HRc img')->attr('src');
            $fullImageUrl = 'https:' . $imageSrcUrl;
            if ($imageSrcUrl) {
                $fileName = time() . '_' . uniqid();
                if (file_put_contents(Yii::getAlias('@uploads' . "/movies/{$fileName}.webp"), file_get_contents($fullImageUrl))) {
                    return "/movies/{$fileName}.webp";
                }
            }

            return null;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить все характеристики фильма
     * @param $htmlDomMovies
     * @return array
     * @throws \Exception
     */
    public function getAboutFilm($htmlDomMovies): array
    {
        try {
            $filmCharacteristics = [];
            $data = $htmlDomMovies->find('div.styles_rowDark__ucbcz');

            foreach ($data as $value) {
                $key = pq($value)->find('div.styles_titleDark___tfMR');
                $value = pq($value)->find('div.styles_valueDark__BCk93 a');
                foreach ($value as $item) {
                    if (pq($item)->text() !== '...' && pq($item)->text() !== 'слова' && pq($item)->text() !== 'сборы') {
                        $filmCharacteristics[trim(pq($key)->text())][] = trim(pq($item)->text());
                    }
                }
            }

            return $filmCharacteristics;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Вернуть время
     * @param $htmlDomMovies
     * @return string|null
     * @throws \Exception
     */
    public function getTime($htmlDomMovies): ?string
    {
        try {
            $data = $htmlDomMovies->find('div.styles_rowDark__ucbcz');

            $time = null;
            foreach ($data as $value) {
                $key = pq($value)->find('div.styles_titleDark___tfMR');
                $value = pq($value)->find('div.styles_valueDark__BCk93 div.styles_valueDark__BCk93');
                foreach ($value as $item) {
                    if (pq($item)->text() !== '...' && pq($item)->text() !== 'слова' && pq($item)->text() !== 'сборы' && trim(pq($key)->text()) === 'Время') {
                        $time = trim(pq($item)->text());
                    }
                }
            }

            return $time;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить описание фильма
     * @param $htmlDomMovies
     * @return string|null
     * @throws \Exception
     */
    public function getDescription($htmlDomMovies): ?string
    {
        try {
            $description = $htmlDomMovies->find('div.styles_synopsisSection__nJoAj div.styles_filmSynopsis__Cu2Oz p.styles_paragraph__wEGPz');

            return trim(pq($description)->text());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить ограничения по возрасту
     * @param $htmlDomMovies
     * @return string|null
     * @throws \Exception
     */
    /*public function getAge($htmlDomMovies): ?string
    {
        try {
            $age = $htmlDomMovies->find('div.styles_rowDark__ucbcz div.styles_valueDark__BCk93 a.styles_restrictionLink__iy4n9 span.styles_rootHighContrast__Bevle');

            return trim(pq($age)->text());
        } catch (\Exception $e) {
            throw $e;
        }
    }*/

    /**
     * @brief Получить рейтинг MPAA обозначение
     * @param $htmlDomMovies
     * @return string|null
     * @throws \Exception
     */
    public function getRatingMpaa($htmlDomMovies): ?string
    {
        try {
            $ratingMpaa = [];
            $data = $htmlDomMovies->find('div.styles_rowDark__ucbcz');
            foreach ($data as $value) {
                $key = pq($value)->find('div.styles_titleDark___tfMR');
                $value = pq($value)->find('div.styles_valueDark__BCk93 a.styles_restrictionLink__iy4n9 span');
                foreach ($value as $item) {
                    if (pq($item)->text() !== '...' && pq($item)->text() !== 'слова' && pq($item)->text() !== 'сборы' && trim(pq($key)->text()) === 'Рейтинг MPAA') {
                        $ratingMpaa = trim(pq($item)->text());
                    }
                }
            }

            return $ratingMpaa;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Получить описание рейтинга MPAA
     * @param $htmlDomMovies
     * @return string|null
     * @throws \Exception
     */
    /*public function getRatingMpaaDescription($htmlDomMovies): ?string
    {
        try {
            $ratingMpaaDesignation = $htmlDomMovies->find('div.styles_rowDark__ucbcz div.styles_valueDark__BCk93 a.styles_restrictionLink__iy4n9 span.styles_restrictionDescription__4j5Pk');

            return trim(pq($ratingMpaaDesignation)->text());
        } catch (\Exception $e) {
            throw $e;
        }
    }*/
}