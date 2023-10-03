<?php

namespace api\modules\v1\models;

use Yii;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;
use common\models\UserRefreshTokens;
use common\models\User;

class JWT
{
    /**
     * @brief Генерация JWT токена
     * @param User $user
     * @return mixed
     */
    public static function generateJwt(User $user): mixed
    {
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        $jwtParams = Yii::$app->params['jwt'];

        return $jwt->getBuilder()
            ->issuedBy($jwtParams['issuer'])
            ->permittedFor($jwtParams['audience'])
            ->identifiedBy($jwtParams['id'], true)
            ->issuedAt($time)
            ->expiresAt($time + $jwtParams['expire'])
            ->withClaim('uid', $user->id)
            ->getToken($signer, $key);
    }

    /**
     * @brief Обновления токена
     * @param User $user
     * @return UserRefreshTokens
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    public static function generateRefreshToken(User $user): UserRefreshTokens
    {
        $userRefreshToken = new UserRefreshTokens([
            'user_id' => $user->id,
            'token' => Yii::$app->security->generateRandomString(200),
            'ip' => Yii::$app->request->userIP,
            'user_agent' => Yii::$app->request->userAgent,
            'created_at' => time(),
        ]);

        if ($userRefreshToken->save()) {
            return $userRefreshToken;
        }

        $errorMessage = "Failed to save the refresh token: {$userRefreshToken->getErrorSummary(true)}";
        throw new ServerErrorHttpException($errorMessage);
    }
}