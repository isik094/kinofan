<?php

namespace api\components;

use yii\db\ActiveRecord;
use common\models\ConfirmEmailCode;

class Code
{
    /** Время жизни в секундах */
    public const LIFE_TIME = 300;
    /** Время между двумя соседними запросами нового кода в секундах */
    public const PAUSE_BETWEEN_QUERY = 60;

    /**
     * @brief Вернуть уникальный код
     * @return string
     */
    public static function getCode(): string
    {
        $code = self::codeGeneration();

        return self::checkUniqueness($code);
    }

    /**
     * @brief Проверка на уникальность кода
     * @param string $code
     * @return string
     */
    public static function checkUniqueness(string $code): string
    {
        while (ConfirmEmailCode::findOne(['code' => $code])) {
            $code = self::codeGeneration();
        }

        return $code;
    }

    /**
     * @brief Случайная генерация кода
     * @return string
     */
    public static function codeGeneration(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetters = substr(str_shuffle($letters), 0, 3);
        $randomNumbers = rand(000, 999);

        return "{$randomLetters}{$randomNumbers}";
    }

    /**
     * @brief Получить последний код для электронной почты
     * @param string $email
     * @return ActiveRecord|null
     */
    public static function getLastCode(string $email): ?ActiveRecord
    {
        return ConfirmEmailCode::find()
            ->where(['email' => $email])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }

    /**
     * @param string $email
     * @param string $code
     * @return ActiveRecord|null
     */
    public static function getActualCode(string $email, string $code): ?ActiveRecord
    {
        return ConfirmEmailCode::find()
            ->where(['and',
                ['email' => $email],
                ['code' => $code],
                ['accepted_at' => null],
                ['>', 'created_at', time() - static::LIFE_TIME],
            ])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    }
}
