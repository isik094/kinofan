<?php

namespace api\modules\v1\traits;

use api\components\ApiFromList;
use common\models\User;

trait UserRoleData
{
    /**
     * @brief User Role Data
     * @return string[]
     */
    public function userRole(): array
    {
        return [
            'role',
            'roleTranslate' => new ApiFromList('role', User::$userRoles),
        ];
    }
}