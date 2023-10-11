<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\modules\v1\models\JWT;
use api\components\ApiResponse;
use api\modules\v1\traits\UserData;
use api\components\ApiResponseException;
use api\modules\v1\models\SendCodeForm;
use api\modules\v1\models\UpdatePasswordForm;

/**
 * @OA\Tag(
 *     name="Account",
 *     description="Методы для получения кода и восстановления пароля"
 * )
 */
class AccountController extends ApiController
{
    use UserData;

    protected bool $isPrivate = false;

    /**
     * @OA\Post(
     *      path="/account/get-code",
     *      summary="Получения кода на электронную почту для подтверждения действий пользователя",
     *      description="Данный эндпоинт отправляет на электронную почту уникальный сгенерированный код для подтверждения пользователя",
     *      operationId="getCode",
     *      tags={"Account"},
     *      @OA\RequestBody(
     *        description = "Электронная почта на который будет выслан уникальный код",
     *        required = true,
     *        @OA\JsonContent(ref="#/components/schemas/SendCodeForm")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Запрос выполнен успешно",
     *          @OA\JsonContent(ref="#/components/schemas/SuccessResponse"),
     *      ),
     *      @OA\Response(
     *          response=406,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Ошибка на стороне сервера",
     *          @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *      ),
     * )
     *
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
     * @OA\Put(
     *       path="/account/reset-password",
     *       summary="Восстановление пароля",
     *       description="Данный эндпоинт восстанавливает пароль пользователя по уникальному коду отправленную на электронную почту, все сессии данного пользователя будут аннулированы на всех устройствах",
     *       operationId="resetPassword",
     *       tags={"Account"},
     *       @OA\RequestBody(
     *         description = "Данные для восстановления пароля",
     *         required = true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePasswordForm")
     *       ),
    *        @OA\Response(
     *          response=200,
     *          description="Запрос выполнен успешно",
     *          @OA\JsonContent(
     *            allOf={
     *              @OA\Schema(
     *                   @OA\Property(
     *                       property="user",
     *                       type="object",
     *                           @OA\Property(property="id", type="integer", example="1", description="ID пользователя"),
     *                           @OA\Property(property="username", type="email",example="qwe@gmail.com", description="Никнейм пользователя"),
     *                           @OA\Property(property="email", type="email", example="qwe@gmail.com", description="Электронная почта пользователя"),
     *                           @OA\Property(property="status", type="integer", example="10", description="Статус пользователя", enum={"0", "9", "10"}),
     *                           @OA\Property(property="statusText", type="string", example="Активен", description="Статус пользователя", enum={"Удален", "Не активен", "Активен"}),
     *                           @OA\Property(property="created_at", type="integer", example="1687464863", description="Время в unixtime"),
     *                   ),
     *              ),
     *              @OA\Schema(ref="#/components/schemas/TokenResponse"),
     *            }
     *          )
     *       ),
     *       @OA\Response(
     *           response=406,
     *           description="Ошибка валидации",
     *           @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Ошибка на стороне сервера",
     *           @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *       ),
     * )
     *
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
