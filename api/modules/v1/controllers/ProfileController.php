<?php

namespace api\modules\v1\controllers;

use Yii;
use api\modules\v1\models\PersonalizationForm;
use api\modules\v1\models\ChangePassword;
use api\modules\v1\models\ProfileUpdateForm;
use api\modules\v1\traits\UserData;
use api\components\ApiResponse;
use api\components\ApiResponseException;
use common\models\User;

class ProfileController extends ApiController
{
    use UserData;

    /**
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
