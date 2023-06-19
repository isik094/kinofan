<?php

namespace api\modules\v1\traits;

use common\models\UserRefreshTokens;
use yii\web\NotFoundHttpException;

trait UserRefreshTokenData
{
    /**
     * @brief Найти токен
     * @param string $refreshToken
     * @return UserRefreshTokens|null
     * @throws NotFoundHttpException
     */
    public function findUserRefreshToken(string $refreshToken): ?UserRefreshTokens
    {
        if (($model = UserRefreshTokens::findOne(['token' => $refreshToken])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}