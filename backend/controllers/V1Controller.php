<?php

namespace backend\controllers;

require_once __DIR__ . '/../../vendor/electrolinux/phpquery/phpQuery/phpQuery.php';

use common\apiClass\CinemaData;
use common\apiClass\CinemaPremieres;
use common\apiClass\CinemaReleases;
use common\models\ErrorLog;
use Yii;
use backend\models\Parser\CaptchaSolving;
use backend\models\Parser\ParserMoviePage;
use backend\models\Parser\DataRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ResponseInterface;
use yii\base\Exception;
use yii\web\Controller;

class V1Controller extends Controller
{
    use DataRequest;

    public function actionApi(int $kp_id)
    {
//        $class = new CinemaReleases();
//        $film = $class->start();
//        $this->print($film);

//        $class = new CinemaPremieres();
//        $film = $class->start();
//        $this->print($film);

        $class = new CinemaData($kp_id);
        $film = $class->run();
        $this->print($film);
    }

    /**
     * @brief Начать парсинг
     * @return void
     * @throws GuzzleException
     */
    public function actionStart()
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['videoCDN']['host'], 'GET', 'short', [
                'query' => ['api_token' => Yii::$app->params['videoCDN']['token'], 'limit' => 100],
            ]);

            if ($response->getStatusCode() === 200) {
                $body = $this->jsonDecodeBody($response);

                if (empty($body->result)) throw new Exception('Произошла ошибка при получении ответа из videoCDN');
                $this->iterateOverData($body);

                echo 'Успешно';
            }
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Перебрать все фильмы и начать парсинг
     * @param object $body
     * @return bool|void
     * @throws GuzzleException
     */
    public function iterateOverData(object $body)
    {
        try {
            if (isset($body->last_page)) {
                for ($i = 1; $i <= $body->last_page; $i++) {
                    $response = $this->sendRequest(Yii::$app->params['videoCDN']['host'], 'GET', 'short', [
                        'query' => ['api_token' => Yii::$app->params['videoCDN']['token'], 'page' => $i, 'limit' => 100],
                    ]);

                    $responseBody = $this->jsonDecodeBody($response);
                    if ($responseBody) $this->parsing($responseBody);
                }

                return true;
            }

            throw new \Exception('Произошла ошибка, нет данных');
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
        }
    }

    /**
     * @brief Получить страницу с контентом и начать парсинг
     * @param $responseBody
     * @return void
     * @throws \Exception|GuzzleException
     */
    public function parsing($responseBody)
    {
        try {
            foreach ($responseBody->data as $value) {
                if ($value->kp_id && $value->type === 'movie' && !Movies::findOne(['kp_id' => $value->kp_id])) {

                    /*$model = new Movies([
                        'kp_id' => $value->kp_id,
                    ]);*/

                    //$htmlPage = new CaptchaSolving(Yii::$app->params['hostKinopoisk'] . "film/{$value->kp_id}/");
                    $htmlPage = new CaptchaSolving('https://www.kinopoisk.ru/film/3498/');
                    $HtmlPageKinopoisk = $htmlPage->getHtmlPage();

                    //echo $HtmlPageKinopoisk;die;
                    //$parser = new ParserMoviePage($HtmlPageKinopoisk, $model);
                    $parser = new ParserMoviePage($HtmlPageKinopoisk);
                    $parser->run();
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Декодировать из формата json в object
     * @param Response $response
     * @return object
     */
    public function jsonDecodeBody(Response $response): object
    {
        return json_decode($response->getBody()->getContents());
    }

    /**
     * @brief Отправка http запросов
     * @param string $baseUri
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return ResponseInterface
     * @throws GuzzleException
     */
    protected function sendRequest(string $baseUri, string $method, string $uri = '', array $params = []): ResponseInterface
    {
        try {
            $client = new Client([
                'headers' => $this->imitationsBrowserHeaders(),
                'base_uri' => $baseUri,
                'cookies' => $this->cookieJar(),
                /*'proxy' => [
                    'http' => $this->proxyServerArray(),
                ],*/
            ]);

            return $client->request($method, $uri, $params);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Вывод данных
     * @param $data
     * @return void
     */
    #[NoReturn] public function print($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }

    /**
     * /test/test
     */
    public function actionTest()
    {
        $result = $this->send('https://kinopoiskapiunofficial.tech/api/v1/staff?filmId=301');
        echo '<pre>';
        print_r($result);
        die;
    }

    /**
     * @brief Отправка запроса
     * @param string $method
     * @param string|null $data
     * @param string $type
     * @return bool|string
     */
    private function send(string $method, ?string $data = null, string $type = 'get'): bool|string
    {
        $headers = [
            //'Authorization: Bearer ' . self::TOKEN,
            'X-API-KEY' => 'dfdb6536-38fd-4930-aa2c-ed620afa9493',
            'Content-Type' => 'application/json',
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        if ($type == 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        }

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}