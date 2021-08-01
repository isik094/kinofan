<?php
namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class UserController extends Controller
{
    /**
     * @brief Создание рутового пользователя
     * @param string $username
     * @param string $password
     * @throws \Throwable
     */
    public function actionCreate($username, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->saveStrict();

        echo 'success';
    }
}