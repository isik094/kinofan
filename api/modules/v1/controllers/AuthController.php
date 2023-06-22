<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\base\Exception;
use yii\web\ServerErrorHttpException;
use api\modules\v1\traits\UserData;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\traits\UserRefreshTokenData;
use common\models\User;
use common\models\LoginForm;
use common\models\UserRefreshTokens;
use frontend\models\SignupForm;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Методы для работы с пользователями"
 * )
 */
class AuthController extends ApiController
{
    use UserData, UserRefreshTokenData;

    /** @var bool Отключаем аутентификацию для контроллера */
    protected bool $isPrivate = false;

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Регистрация нового пользователя",
     *     description="Данный эндпоинт создаeт нового пользователя с ролью user",
     *     operationId="addUser",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *       description = "Данные для регистрации пользователя",
     *       required = true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"username": "isik@yandex.ru", "password": "qwert123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пустой или неправильный токен",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Запрещено",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдено",
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *     ),
     * )
     *
     * @brief Регистрация
     * @return ApiResponse|ApiResponseException
     */
    public function actionRegister(): ApiResponse|ApiResponseException
    {
        try {
            $model = new SignupForm();
            $model->username = Yii::$app->request->post('username');
            $model->password = Yii::$app->request->post('password');

            if ($model->validate() && $user = $model->signup()) {
                return new ApiResponse(false, $this->makeObject($user, $this->userData()));
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Авторизация пользователя",
     *     description="Данный эндпоинт выдает accessToken и refreshToken",
     *     operationId="authUser",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *       description = "Данные для авторизации пользователя",
     *       required = true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"username": "isik@yandex.ru", "password": "qwert123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пустой или неправильный токен",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Запрещено",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдено",
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *     ),
     * )
     *
     * @brief Авторизация
     * @return ApiResponse|ApiResponseException
     */
    public function actionLogin(): ApiResponse|ApiResponseException
    {
        try {
            $model = new LoginForm();
            $model->username = Yii::$app->request->post('username');
            $model->password = Yii::$app->request->post('password');

            if ($user = $model->login()) {
                $accessToken = $this->generateJwt($user);
                $userRefreshToken = $this->generateRefreshToken($user);

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

    /**
     * @OA\Post(
     *     path="/auth/refresh",
     *     summary="Получить новый временный accessToken",
     *     description="Данный эндпоинт выдает refreshToken и новый временный accessToken",
     *     operationId="newRefreshToken",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *       description = "Данные для получения accessToken",
     *       required = true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="refreshToken",
     *                     type="string",
     *                 ),
     *                 example={"refreshToken": "dsweERTWEg25GRdghedgaeojkpojkRERGHAABRH"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пустой или неправильный токен",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Запрещено",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдено",
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *     ),
     * )
     *
     * @brief Получить новый access_token пользователю
     * @return ApiResponse|ApiResponseException|\yii\web\UnauthorizedHttpException
     * @throws \Throwable
     */
    public function actionNewRefreshToken(): ApiResponse|\yii\web\UnauthorizedHttpException|ApiResponseException
    {
        try {
            $refreshToken = Yii::$app->request->post('refreshToken');
            $userRefreshToken = $this->findUserRefreshToken($refreshToken);
            $user = User::findOne(['and', 'id' => $userRefreshToken->user_id, 'status' => User::STATUS_ACTIVE]);

            if (!$user) {
                $userRefreshToken->delete();
                return new \yii\web\UnauthorizedHttpException('The user is inactive.');
            }

            $response = [
                'accessToken' => (string)$this->generateJwt($user),
                'refreshToken' => $refreshToken,
            ];

            return new ApiResponse(false, $response);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Delete(
     *     path="/auth/refresh",
     *     summary="Разлогинить пользователя и удалить refreshToken",
     *     description="Данный эндпоинт удаляет refreshToken для пользователя, который вышел из системы",
     *     operationId="deleteRefreshToken",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *       description = "Данные для получения accessToken",
     *       required = true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="refreshToken",
     *                     type="string",
     *                 ),
     *                 example={"refreshToken": "dsweERTWEg25GRdghedgaeojkpojkRERGHAABRH"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пустой или неправильный токен",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Запрещено",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдено",
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *     ),
     * )
     *
     * @brief Выход из системы на устройстве
     * @return ServerErrorHttpException|ApiResponse|ApiResponseException
     * @throws \Throwable
     */
    public function actionDeleteRefreshToken(): ServerErrorHttpException|ApiResponse|ApiResponseException
    {
        try {
            $userRefreshToken = $this->findUserRefreshToken(Yii::$app->request->post('refreshToken'));
            if ($userRefreshToken && !$userRefreshToken->delete()) {
                return new \yii\web\ServerErrorHttpException('Failed to delete the refresh token.');
            }

            return new ApiResponse(false, 'Deleted successfully');
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @brief Генерация JWT токена
     * @param User $user
     * @return mixed
     */
    private function generateJwt(User $user): mixed
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
    private function generateRefreshToken(User $user): UserRefreshTokens
    {
        $refreshToken = Yii::$app->security->generateRandomString(200);

        // TODO: Don't always regenerate - you could reuse existing one if user already has one with same IP and user agent
        $userRefreshToken = new UserRefreshTokens([
            'user_id' => $user->id,
            'token' => $refreshToken,
            'ip' => Yii::$app->request->userIP,
            'user_agent' => Yii::$app->request->userAgent,
            'created_at' => time(),
        ]);

        if (!$userRefreshToken->save()) {
            throw new ServerErrorHttpException('Failed to save the refresh token: '. $userRefreshToken->getErrorSummary(true));
        }

        return $userRefreshToken;
    }
}