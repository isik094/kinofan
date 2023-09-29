<?php

namespace api\modules\v1\traits;

use common\models\User;
use api\components\ApiGetter;
use api\components\ApiFromList;
use yii\web\NotFoundHttpException;

trait UserData
{
    use UserRoleData;
    use ProfileTrait;
    use UserCountryCinemaTrait;
    use UserGenreCinemaTrait;
    use UserHobbiesCinemaTrait;

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
            'created_at',
            'profile' => new ApiGetter('profile', $this->profileData()),
            'personalizationCountry' => new ApiGetter('userCountryCinema', $this->userCountryCinemaData()),
            'personalizationGenre' => new ApiGetter('userGenreCinema', $this->userGenreCinemaData()),
            'personalizationHobbies' => new ApiGetter('userHobbiesCinema', $this->userHobbiesCinemaData()),
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
        if (($model = User::findOne(['id' => $id, 'status' => User::STATUS_ACTIVE])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not found');
    }
}