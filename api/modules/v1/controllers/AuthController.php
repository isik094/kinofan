<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
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
 *     description="Методы для авторизации/регистрации"
 * )
 */
class AuthController extends ApiController
{
    use UserData;
    use UserRefreshTokenData;

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
     *       @OA\JsonContent(ref="#/components/schemas/SignupForm")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка на стороне сервера",
     *         @OA\JsonContent(ref="#/components/schemas/ServerError"),
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

            if ($user = $model->signup()) {
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
     *       @OA\JsonContent(ref="#/components/schemas/LoginForm")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *         @OA\JsonContent(
     *           allOf={
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="user",
     *                      type="object",
     *                          @OA\Property(property="id", type="integer", example="1", description="ID пользователя"),
     *                          @OA\Property(property="username", type="email",example="qwe@gmail.com", description="Никнейм пользователя"),
     *                          @OA\Property(property="email", type="email", example="qwe@gmail.com", description="Электронная почта пользователя"),
     *                          @OA\Property(property="status", type="integer", example="10", description="Статус пользователя", enum={"0", "9", "10"}),
     *                          @OA\Property(property="statusText", type="string", example="Активен", description="Статус пользователя", enum={"Удален", "Не активен", "Активен"}),
     *                          @OA\Property(property="created_at", type="integer", example="1687464863", description="Время в unixtime"),
     *                  ),
     *             ),
     *             @OA\Schema(ref="#/components/schemas/TokenResponse"),
     *           }
     *         )
     *     ),
     *     @OA\Response(
     *         response=406,
     *         description="Ошибка валидации",
     *         @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка на стороне сервера",
     *         @OA\JsonContent(ref="#/components/schemas/ServerError"),
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
     *     operationId="refresh",
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
     *                     example="1seSXoHn2a1mvq-oQh_xPaquZ1dtTJGmkKgbKAo8bftxk7vGSrf-g9Hi0HytY-qpnXmaYGM6v6rQDzxrvUzcUxnP4Hntux8EXkWMTD9IfafIxE3zNLLJ9Vi0jzGHs7DOH5p2k6UK_wJq-y1SRgpgJuXLTSUi6SpZSNtg5iyFjfcDA6h8vLE7lgQSas4Nga0vKelmfGTo"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *         @OA\JsonContent(ref="#/components/schemas/TokenResponse"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Не найдено",
     *         @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка на стороне сервера",
     *         @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *     ),
     * )
     *
     * @brief Получить новый access_token пользователю
     * @return ApiResponse|ApiResponseException|\yii\web\UnauthorizedHttpException
     * @throws \Throwable
     */
    public function actionRefresh(): ApiResponse|\yii\web\UnauthorizedHttpException|ApiResponseException
    {
        try {
            $refreshToken = Yii::$app->request->post('refreshToken');
            $userRefreshToken = $this->findUserRefreshToken($refreshToken);
            $user = User::findOne(['and', 'id' => $userRefreshToken->user_id, 'status' => User::STATUS_ACTIVE]);

            if ($user === null) {
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
