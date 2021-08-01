<?php
namespace backend\controllers;


use Yii;
use yii\web\Controller;

class V1Controller extends Controller
{
    const HOST = 'http://api.acqcrypto/v1';

    const TOKEN = '123123123';

    /**
     * /test/test
     */
    public function actionTest()
    {
        $data = [
            'test' => 'test',
        ];

        $result = $this->send('/test/test', 'post', $data);
        echo '<pre>'; print_r($result); die;
    }

    /**
     * @brief Отправка запроса
     * @param string $method
     * @param string $type
     * @param array $data
     * @return bool|string
     */
    private function send($method, $type = 'get', array $data = [])
    {
        $headers = [
            'Authorization: Bearer ' . self::TOKEN,
//            'Content-Type: application/json'
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, self::HOST . $method);
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