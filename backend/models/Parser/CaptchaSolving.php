<?php

namespace backend\models\Parser;

use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

require_once __DIR__ . '/../../../vendor/electrolinux/phpquery/phpQuery/phpQuery.php';

class CaptchaSolving
{
    use DataRequest;

    /** @brief Хост кинопоиска */
    private const HOST_KINOPOISK = 'https://www.kinopoisk.ru/';

    /**
     * @var string
     */
    public string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @brief Получить страницу после прохождения smart captcha
     * @return void
     * @throws GuzzleException
     */
    public function getHtmlPage()
    {
        try {
            $HtmlPageKinopoisk = \phpQuery::newDocument(file_get_contents($this->url));
            $firstStageCaptchaResponse = $this->firstStageCaptcha($HtmlPageKinopoisk);

            $entry = $firstStageCaptchaResponse->find('title');
            if (pq($entry)->text() === 'Ой!') {
                return $this->secondStageCaptcha($firstStageCaptchaResponse);
            } else {
                return $firstStageCaptchaResponse;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Первая стадия прохождения smart captcha Yandex
     * @param $HtmlPageKinopoisk
     * @return mixed
     * @throws GuzzleException
     */
    protected function firstStageCaptcha($HtmlPageKinopoisk): mixed
    {
        try {
            $entry = $HtmlPageKinopoisk->find('title');

            if (pq($entry)->text() === 'Ой!') {
                $inputRole = $HtmlPageKinopoisk->find('input')->attr('role');
                if ($inputRole === 'checkbox') {
                    if ($yandexSmartCaptchaActionClick = $HtmlPageKinopoisk->find('div.Container form')->attr('action')) {
                        $response = $this->sendRequest(self::HOST_KINOPOISK, 'GET', $yandexSmartCaptchaActionClick);

                        $htmlDomSmartCaptcha = \phpQuery::newDocument($response->getBody()->getContents());
                        $imageSrcUrl = $htmlDomSmartCaptcha->find('div.AdvancedCaptcha-View img')->attr('src');
                        $entry = $htmlDomSmartCaptcha->find('title');

                        if ($imageSrcUrl || pq($entry)->text() !== 'Ой!') {
                            return $htmlDomSmartCaptcha;
                        } else {
                            $this->firstStageCaptcha($htmlDomSmartCaptcha);
                        }
                    }
                }
            }

            return $HtmlPageKinopoisk;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Пройти вторую стадию с капчей изображением
     * @param $HtmlPageKinopoisk
     * @return \phpQueryObject|\QueryTemplatesParse|\QueryTemplatesSource|\QueryTemplatesSourceQuery|void
     * @throws GuzzleException
     */
    protected function secondStageCaptcha($HtmlPageKinopoisk)
    {
        try {
            $imageSrcUrl = $HtmlPageKinopoisk->find('div.AdvancedCaptcha-View img')->attr('src');

            if ($imageSrcUrl) {
                if (file_put_contents(Yii::getAlias('@uploads' . '/captcha/image.png'), file_get_contents($imageSrcUrl))) {

                    $ruCaptcha = new RuCaptchaApi();
                    $responseRuCaptcha = $ruCaptcha->send();

                    $urlReplaceSpace = str_replace(' ', '', $responseRuCaptcha);
                    $urlEncode = urlencode($urlReplaceSpace);

                    $yandexUrl = $HtmlPageKinopoisk->find('form')->attr('action');
                    $url = $yandexUrl . '&rep=' . $urlEncode;
                    $response = $this->sendRequest(self::HOST_KINOPOISK, 'GET', $url);

                    $htmlDomMoviesPage = \phpQuery::newDocument($response->getBody()->getContents());
                    $entry = $htmlDomMoviesPage->find('title');

                    if (pq($entry)->text() !== 'Ой!') {
                        return $htmlDomMoviesPage;
                    } else {
                        $this->secondStageCaptcha($htmlDomMoviesPage);
                    }
                }
            } else {
                $this->secondStageCaptcha($HtmlPageKinopoisk);
            }
        } catch (\Exception $e) {
            throw $e;
        }
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
}