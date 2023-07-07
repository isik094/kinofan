<?php

namespace common\apiClass;

use Yii;
use common\models\ErrorLog;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Kinopoiskapiunofficial
{
    /**
     * @brief Получить данные о конкретном актере
     * @param int $person_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getStaffPerson(int $person_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v1'], "staff/{$person_id}");

            if ($response?->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить данные об актерах, режиссерах и так далее
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getStaff(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v1'], "staff", [
                'query' => ['filmId' => $kp_id],
            ]);

            if ($response?->getStatusCode() === 200) return (object)json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить список цифровых релизов.
     * @param int $year
     * @param string $month
     * @param int|null $page
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmReleases(int $year, string $month, ?int $page = 1)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_1'], "films/releases", [
                'query' => ['year' => $year, 'month' => $month, 'page' => $page],
            ]);

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить список кинопремьер
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmSequelsAndPrequels(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_1'], "films/{$kp_id}/sequels_and_prequels");

            if ($response->getStatusCode() === 200) return (object)json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить список кинопремьер
     * @param int $year
     * @param string $month
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmPremieres(int $year, string $month)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/premieres", [
                'query' => ['year' => $year, 'month' => $month],
            ]);

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить изображения связанные с фильмом
     * @param int $kp_id
     * @param string|null $type
     * @param int|null $page
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmImages(int $kp_id, ?string $type = null, ?int $page = 1)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/images", [
                'query' => ['type' => $type, 'page' => $page],
            ]);

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить список похожих фильмов
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmSimilar(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/similars");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить трейлеры, тизеры, видео для фильма по kinopoisk film id
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmVideos(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/videos");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить данные о наградах и премиях фильма.
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmAwards(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/awards");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить данные о бюджете и сборах.
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmBoxOffice(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/box_office");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить данные о прокате в разных странах.
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmDistributions(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/distributions");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить список фактов и ошибок в фильме
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmFact(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/facts");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить данные о сезонах для сериалов
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilmSeason(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}/seasons");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить данные о фильме
     * @param int $kp_id
     * @return object|void
     * @throws GuzzleException
     */
    public function getFilm(int $kp_id)
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['kinopoiskapiunofficial']['host_v2_2'], "films/{$kp_id}");

            if ($response->getStatusCode() === 200) return $this->jsonDecodeBody($response);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Декодировать из формата json в object
     * @param Response $response
     * @return object
     */
    private function jsonDecodeBody(Response $response): object
    {
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @brief Отправка http запросов
     * @param string $baseUri
     * @param string $uri
     * @param array $params
     * @return ResponseInterface|void
     * @throws GuzzleException
     */
    private function sendRequest(string $baseUri, string $uri = '', array $params = [])
    {
        try {
            $client = new Client([
                'headers' => [
                    'X-API-KEY' => Yii::$app->params['kinopoiskapiunofficial']['apiKey'],
                    'Content-Type' => 'application/json',
                ],
                'base_uri' => $baseUri,
            ]);

            return $client->request('GET', $uri, $params);
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }
}