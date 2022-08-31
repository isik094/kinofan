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

    public function run()
    {
        $data = [];
        $htmlDomMovies = \phpQuery::newDocument($this->htmlPageMovie);

        $data['title_ru'] = $this->getTitleRu($htmlDomMovies);
        $data['title_eng'] = $this->getTitleEng($htmlDomMovies);
        $data['short_description'] = $this->getShortDescription($htmlDomMovies);
        $data['path'] = $this->getImages($htmlDomMovies);
        $data['production_year'] = $this->getProductionYear($htmlDomMovies);
        //$data['tagline'] = $this->getTagline($htmlDomMovies);


        echo '<pre>';
        print_r($data); die;

        $ratingMpaa = $htmlDomMovies->find('div.styles_valueDark__BCk93 a.styles_restrictionLink__iy4n9 span.styles_rootHighContrast__Bevle');
        $data['rating_mpaa'] = pq($ratingMpaa)->text();

        $ratingMpaaDesignation = $htmlDomMovies->find('div.styles_valueDark__BCk93 a.styles_restrictionLink__iy4n9 span.styles_restrictionDescription__4j5Pk');
        $data['rating_mpaa_designation'] = pq($ratingMpaaDesignation)->text();

        $time = $htmlDomMovies->find('div.styles_valueDark__BCk93 styles_value__g6yP4 div.styles_valueDark__BCk93 styles_value__g6yP4');
        $data['time'] = pq($time)->text();

        $description = $htmlDomMovies->find('div.styles_synopsisSection__nJoAj div.styles_filmSynopsis__Cu2Oz p.styles_paragraph__wEGPz');
        $data['description'] = pq($description)->text();

        echo '<pre>';
        print_r($data); die;
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
     * @brief Получить год производства
     * @param $htmlDomMovies
     * @return string
     * @throws \Exception
     */
    public function getProductionYear($htmlDomMovies): string
    {
        try {
            $movieData = [];
            $productionYear = $htmlDomMovies->find('div.styles_valueDark__BCk93 a');
            foreach ($productionYear as $value) {
                $movieData[] = pq($value)->text();
            }

            echo '<pre>';
            print_r($movieData);
            die;

            if (!$movieData[0]) {
                $movieData2 = [];
                $productionYear = $htmlDomMovies->find('div.styles_valueLight__nAaO3 a');
                foreach ($productionYear as $value) {
                    $movieData2[] = pq($value)->text();
                }

                return $movieData2[0];
            }

            return $movieData[0];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getTagline($htmlDomMovies)
    {
        try {
            $tagline = $htmlDomMovies->find('div.styles_empty__Qfc3a div.styles_valueDark__BCk93');
            $data['tagline'] = pq($tagline)->text();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}