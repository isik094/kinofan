<?php

namespace console\controllers;

use Yii;
use api\models\UserRole;
use common\models\User;
use common\models\ErrorLog;
use yii\console\Controller;

class UserController extends Controller
{
    /**
     * @brief Создание рутового пользователя
     * @param string $username
     * @param string $password
     * @param string $role
     * @return void
     */
    public function actionCreate(string $username, string $password, string $role)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->username = $username;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;

            if ($user->saveStrict()) {
                $userRole = new UserRole([
                    'user_id' => $user->id,
                    'role' => $role,
                    'created_at' => time(),
                ]);
                $userRole->saveStrict();
            }
            $transaction->commit();

            echo 'Successfully created';
        } catch (\Exception $exception) {
            $transaction->rollBack();
            ErrorLog::createLog($exception);
        }
    }
}