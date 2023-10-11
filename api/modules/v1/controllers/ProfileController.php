<?php

namespace api\modules\v1\controllers;

use Yii;
use OpenApi\Annotations as OA;
use api\modules\v1\models\PersonalizationForm;
use api\modules\v1\models\ChangePassword;
use api\modules\v1\models\ProfileUpdateForm;
use api\modules\v1\traits\UserData;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use common\models\User;

/**
 * @OA\Tag(
 *     name="Profile",
 *     description="Методы для изменение пользовательских данных"
 * )
 */
class ProfileController extends ApiController
{
    use UserData;

    /**
     * @OA\Put(
     *        path="/profile",
     *        summary="Обновления данных в профиле пользователя",
     *        description="Данный эндпоинт редактирует данные пользователя во вкладке профиль",
     *        operationId="updateProfile",
     *        tags={"Profile"},
     *        @OA\RequestBody(
     *          description = "Данные для восстановления пароля",
     *          required = true,
     *          @OA\JsonContent(ref="#/components/schemas/ProfileUpdateForm")
     *        ),
     *         @OA\Response(response=200, description="Запрос выполнен успешно",
     *           @OA\JsonContent()
     *        ),
     *        @OA\Response(response=406, description="Ошибка валидации",
     *            @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *        ),
     *        @OA\Response(response=500, description="Ошибка на стороне сервера",
     *            @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *        ),
     *        security={{"bearerAuth":{}}},
     * )
     *
     * @brief Редактировать профиль пользователя
     * @return ApiResponse|ApiResponseException
     */
    public function actionUpdate(): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $request = Yii::$app->request;

            $model = new ProfileUpdateForm(['user' => $user]);
            $model->surname = $request->post('surname') ?? $user->profile->surname;
            $model->name = $request->post('name') ?? $user->profile->name;
            $model->patronymic = $request->post('patronymic') ?? $user->profile->patronymic;
            $model->vk = $request->post('vk') ?? $user->profile->vk;
            $model->telegram = $request->post('telegram') ?? $user->profile->telegram;
            $model->email = $request->post('email') ?? $user->email;

            if ($user = $model->update()) {
                return new ApiResponse(
                    false,
                    $this->makeObject($user, $this->userData())
                );
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Put(
     *         path="/profile/change-password",
     *         summary="Изменить пароль текущему пользователю",
     *         description="Данный эндпоинт изменяет пароль текущего пользователя",
     *         operationId="сhangePassword",
     *         tags={"Profile"},
     *         @OA\RequestBody(
     *           description = "Данные для восстановления пароля",
     *           required = true,
     *           @OA\JsonContent(ref="#/components/schemas/ChangePassword")
     *         ),
     *          @OA\Response(response=200, description="Запрос выполнен успешно",
     *            @OA\JsonContent()
     *         ),
     *         @OA\Response(response=406, description="Ошибка валидации",
     *             @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *         ),
     *         @OA\Response(response=500, description="Ошибка на стороне сервера",
     *             @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *         ),
     *         security={{"bearerAuth":{}}},
     * )
     *
     * @brief Изменить пароль
     * @return ApiResponse|ApiResponseException
     */
    public function actionChangePassword(): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $request = Yii::$app->request;

            $model = new ChangePassword(['user' => $user]);
            $model->currentPassword = $request->post('currentPassword');
            $model->newPassword = $request->post('newPassword');

            if ($model->changePassword()) {
                return new ApiResponse(false, true);
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }

    /**
     * @OA\Put(
     *          path="/profile/personalization",
     *          summary="Изменить персонализацию текущему пользователю",
     *          description="Данный эндпоинт изменяет персонализацию текущего пользователя",
     *          operationId="сhangePersonalization",
     *          tags={"Profile"},
     *          @OA\RequestBody(
     *            description = "Данные для персонализации профиля пользователя",
     *            required = true,
     *            @OA\JsonContent(ref="#/components/schemas/PersonalizationForm")
     *          ),
     *           @OA\Response(response=200, description="Запрос выполнен успешно",
     *             @OA\JsonContent()
     *          ),
     *          @OA\Response(response=406, description="Ошибка валидации",
     *              @OA\JsonContent(ref="#/components/schemas/ValidateForm"),
     *          ),
     *          @OA\Response(response=500, description="Ошибка на стороне сервера",
     *              @OA\JsonContent(ref="#/components/schemas/ServerError"),
     *          ),
     *          security={{"bearerAuth":{}}},
     * )
     *
     * @brief Персонализация
     * @return ApiResponse|ApiResponseException
     */
    public function actionPersonalization(): ApiResponse|ApiResponseException
    {
        try {
            $user = User::getCurrent();
            $request = Yii::$app->request;

            $model = new PersonalizationForm(['user' => $user]);
            $model->sex = $request->post('sex') ?? $user->profile->sex;
            $model->birthday = $request->post('birthday') ?? $user->profile->birthday;
            $model->country_id = $request->post('country_id');
            $model->genre_ids = $request->post('genre_ids');
            $model->hobbies_ids = $request->post('hobbies_ids');

            if ($user = $model->updatePersonalization()) {
                return new ApiResponse(
                    false,
                    $this->makeObject($user, $this->userData()),
                );
            }

            return new ApiResponse(true, $model->errors);
        } catch (\Exception $e) {
            return new ApiResponseException($e);
        }
    }
}
