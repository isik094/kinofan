<?php

namespace api\modules\v1\controllers;

use Yii;
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
     * @param int $id
     * @return ApiResponse|ApiResponseException
     */
    public function actionUpdate(int $id): ApiResponse|ApiResponseException
    {
        try {
            $user = $this->findUser($id);
            $request = Yii::$app->request;

            $model = new ProfileUpdateForm(['user' => $user]);
            $model->surname = $request->post('surname');
            $model->name = $request->post('name');
            $model->patronymic = $request->post('patronymic');
            $model->vk = $request->post('vk');
            $model->telegram = $request->post('telegram');
            $model->email = $request->post('email');

            if ($user = $model->update()) {
                return new ApiResponse(false, $this->makeObject($user, $this->userData()));
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
}
