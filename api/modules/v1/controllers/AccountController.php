<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\JWT;
use api\components\ApiResponse;
use api\modules\v1\traits\UserData;
use api\components\ApiResponseException;
use api\modules\v1\models\SendCodeForm;
use api\modules\v1\models\UpdatePasswordForm;

class AccountController extends ApiController
{
    use UserData;

    protected bool $isPrivate = false;

    /**
     * @brief Получть на почту код для восстановления пароля
     * @return ApiResponse|ApiResponseException
     */
    public function actionGetCode(): ApiResponse|ApiResponseException
    {
        try {
            $model = new SendCodeForm();
            $model->email = Yii::$app->request->post('email');

            if ($model->sendCode()) {
                return new ApiResponse(false, true);
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @brief Сбросить пароль
     * @return ApiResponse|ApiResponseException
     */
    public function actionResetPassword(): ApiResponse|ApiResponseException
    {
        try {
            $request = Yii::$app->request;
            $model = new UpdatePasswordForm();
            $model->email = $request->post('email');
            $model->code = $request->post('code');
            $model->password = $request->post('password');

            if ($user = $model->updatePassword()) {
                $accessToken = JWT::generateJwt($user);
                $userRefreshToken = JWT::generateRefreshToken($user);

                $response = [
                    'user' => $this->makeObject($user, $this->userData()),
                    'accessToken' => (string)$accessToken,
                    'refreshToken' => $userRefreshToken->token,
                ];

                return new ApiResponse(false, $response);
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}
