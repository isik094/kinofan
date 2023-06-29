<?php

namespace backend\controllers;

require_once __DIR__ . '/../../vendor/electrolinux/phpquery/phpQuery/phpQuery.php';

use Yii;
use backend\models\Parser\CaptchaSolving;
use backend\models\Parser\ParserMoviePage;
use common\models\Movies;
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

    private const TOKEN_CDN = 'ieTeLZNAZe0Dxk3hm4ry52q8PacNrS0A';

    /** @brief Хост кинопоиска */
    public const HOST_KINOPOISK = 'https://www.kinopoisk.ru/';

    /**
     * @brief Начать парсинг
     * @return void
     * @throws Exception
     * @throws GuzzleException
     */
    public function actionStart()
    {
        try {
            $response = $this->sendRequest(Yii::$app->params['hostCDN'], 'GET', 'short', [
                'query' => ['api_token' => Yii::$app->params['tokenCDN'], 'limit' => 100],
            ]);

            if ($response->getStatusCode() === 200) {
                if ($body = $this->jsonDecodeBody($response)) {
                    $this->iterateOverData($body);

                    echo 'Успешно';
                }
            }

            throw new Exception('Произошла ошибка при получении ответа из videoCDN');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Перебрать все фильмы и начать парсинг
     * @param object $body
     * @return bool
     * @throws GuzzleException
     */
    public function iterateOverData(object $body): bool
    {
        try {
            for ($i = 1; $i <= $body->last_page; $i++) {
                $response = $this->sendRequest(Yii::$app->params['hostCDN'], 'GET', 'short', [
                    'query' => ['api_token' => Yii::$app->params['tokenCDN'], 'page' => $i, 'limit' => 100],
                ]);

                $responseBody = $this->jsonDecodeBody($response);
                if ($responseBody) $this->parsing($responseBody);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
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

                    //$htmlPage = new CaptchaSolving(self::HOST_KINOPOISK . "film/{$value->kp_id}/");
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
        $data = [
            'test' => 'test',
        ];

        $result = $this->send('/test/test', 'post', $data);
        echo '<pre>';
        print_r($result);
        die;
    }

    /**
     * @brief Отправка запроса
     * @param string $method
     * @param string $type
     * @param string $data
     * @return bool|string
     */
    private function send($method, $type = 'get', string $data)
    {
        $headers = [
            //'Authorization: Bearer ' . self::TOKEN,
            'Content-Type: application/json'
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