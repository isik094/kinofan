<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use common\models\User;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use api\modules\v1\traits\UserRefreshTokenData;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

/**
 * @OA\Tag(
 *     name="User",
 *     description="Методы для работы с пользователями"
 * )
 */
class UserController extends ApiController
{
    use UserRefreshTokenData;

    /**
     * @OA\Delete(
     *     path="/user/logout",
     *     summary="Разлогинить пользователя и удалить refreshToken",
     *     description="Данный эндпоинт удаляет refreshToken для пользователя, который вышел из системы",
     *     operationId="logout",
     *     tags={"User"},
     *     @OA\RequestBody(
     *       description = "refreshToken пользователя, которого нужно разлогинить и удалить отправленный токен",
     *       required = true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="refreshToken",
     *                     type="string",
     *                     example="UjOPobe6bv_iBGMrrqyPEhv3ebZbamp78JBneh8CyvL0-mPTUpWinsys0OK8yeM6-TEB9hx8QHOaITJfrscLtmmGDo0qexMxyQMoA940LJJDwswqLGvnlzujim4K6wGxOey9uvlwoxyB9MMcROrR5p3gnmAPzFiTda7GQSEQXwlGb5DfkAG110G6-s_8_AZ-PgemnHt0",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запрос выполнен успешно",
     *         @OA\JsonContent(oneOf={@OA\Schema(
     *             @OA\Property(property="error", type="boolean", example="false", description="Булево обозначение ошибки"),
     *             @OA\Property(property="message", type="boolean", example="true", description="Текст сообщения ошибки на стороне сервера"),
     *             @OA\Property(property="status", type="integer", example="200", description="Код ошибки"),
     *         )}),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Пустой или неправильный токен",
     *         @OA\JsonContent(ref="#/components/schemas/Unauthorized"),
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Запрещено",
     *         @OA\JsonContent(ref="#/components/schemas/Forbidden"),
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
     *
     *     security={ {"accessToken": {}} }
     * )
     *
     * @brief Выход из системы на устройстве
     * @return ServerErrorHttpException|ApiResponse|ApiResponseException
     * @throws \Throwable
     */
    public function actionLogout(): ServerErrorHttpException|ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $userRefreshToken = $this->findUserRefreshToken(Yii::$app->request->post('refreshToken'));
            if ($user->id !== $userRefreshToken?->user_id) {
                throw new ForbiddenHttpException('Access is denied');
            }

            if ($userRefreshToken?->delete()) {
                return new ApiResponse(false, true);
            }

            return new \yii\web\ServerErrorHttpException('Failed to delete the refresh token.');
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}
