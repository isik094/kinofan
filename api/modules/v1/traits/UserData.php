<?php

namespace api\modules\v1\traits;

use common\models\User;
use api\components\ApiGetter;
use api\components\ApiFromList;
use yii\web\NotFoundHttpException;

trait UserData
{
    use UserRoleData;

    /**
     * @brief User Data
     * @return string[]
     */
    public function userData(): array
    {
        return [
            'id',
            'username',
            'email',
            'status',
            'statusText' => new ApiFromList('status', User::$userStatus),
            'createdAt',
            'userRoles' => new ApiGetter('userRoles', $this->userRole()),
        ];
    }

    /**
     * @brief Получить пользователя
     * @param int $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function findUser(int $id): User
    {
        if ($user = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE])) {
            return $user;
        }

        throw new NotFoundHttpException('Not found');
    }
}