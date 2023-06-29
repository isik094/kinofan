<?php

namespace backend\models\Parser;

use GuzzleHttp\Cookie\CookieJar;

trait DataRequest
{
    /**
     * @brief Заголовки для имитации браузера
     * @return array
     */
    public function imitationsBrowserHeaders(): array
    {
        return [
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'Connection' => 'keep-alive',
            'Host' => 'static-mon.yandex.net',
            'Origin' => 'https://www.kinopoisk.ru',
            'Referer' => 'https://www.kinopoisk.ru/',
            'sec-ch-ua' => '"Chromium";v="106", "Google Chrome";v="106", "Not;A=Brand";v="99"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'cross-site',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36',
        ];
    }

    /**
     * @brief Вернуть случайно один из прокси серверов
     * @return string
     */
    public function proxyServerArray(): string
    {
        $proxy = [
            1 => '185.15.172.212:3128',
            '213.171.63.210:41890',
            '82.146.57.111:8118',
            '212.46.230.102:6969',
            '188.242.219.58:8080',
        ];

        return $proxy[rand(1, count($proxy))];
    }

    /**
     * @brief Куки из сессии кинопоиска
     * @return CookieJar
     */
    public function cookieJar(): CookieJar
    {
        return CookieJar::fromArray(
            [
                'cycada' => '2+3Dyxjz7C50UolRu2x566fOjcxzlLtGCfvXUjCivjo=',
                'PHPSESSID' => '4fc6e1ab8cf5e8aba50c617560964a4b',
                'spravka' => 'dD0xNjYxMjQ3NzM0O2k9ODcuMTE3LjE4OS4yMjk7RD0xODkzMjMyMjAzMDE4NkU4QjdERTJFRTNDREU2N0IwQTI5Mjc5MDk0RkMwMzc4RUY1QTlGRDJBODk3RTM2RTFDRUIwMTQxM0M7dT0xNjYxMjQ3NzM0MDUwNjM5Nzk2O2g9N2U2M2FiYTUzM2JhNjVmMTQ5NDYwOWQ1NjVmYjNhNWE=',
            ],
            'https://www.kinopoisk.ru/');
    }
}