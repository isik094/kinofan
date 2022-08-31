<?php
namespace backend\models\Parser;

use Yii;
use TwoCaptcha\TwoCaptcha;

class RuCaptchaApi
{
    /*** @brief Ключ для API ruCaptcha */
    const API_KEY = '1785b59f775e5c54e32687e018b29186';

    /**
     * @var string
     */
    public string $path;

    /**
     * @brief Получить путь к файлу captcha
     */
    public function __construct()
    {
        $this->path = Yii::getAlias('@uploads' . '/captcha/image.png');
    }

    /**
     * @brief Отправить на решение файл captcha
     * @return false|string
     */
    public function send(): bool|string
    {
        try {
            $solver = new TwoCaptcha(self::API_KEY);
            $result = $solver->normal($this->path);

            if ($result) return $result->code;

            return false;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}