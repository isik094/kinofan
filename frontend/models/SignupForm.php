<?php

namespace frontend\models;

use Yii;
use api\models\UserRole;
use common\models\Profile;
use OpenApi\Annotations as OA;
use yii\base\Model;
use common\models\User;

/**
 *
 * @OA\Schema(
 *     schema="SignupForm",
 *     required={"username", "password"},
 *     @OA\Property(property="username", type="string", example="isik@yandex.ru", description="Электронная почта пользователя"),
 *     @OA\Property(property="password", type="string", example="qwerty", description="Пароль"),
 * )
 *
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'email'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой пользователь уже зарегистрирован.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null
     * @throws \Exception
     */
    public function signup(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->username;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->saveStrict();
            $this->createProfile($user);
            $this->createRole($user);
            $user->sendEmail();
            $transaction->commit();

            return $user;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @brief Создать запись в таблице профиль пользователя
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    protected function createProfile(User $user): bool
    {
        $profile = new Profile();
        $profile->user_id = $user->id;

        return $profile->saveStrict();
    }

    /**
     * @brief Создать роль пользователя при регистрации
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    protected function createRole(User $user): bool
    {
        $userRole = new UserRole();
        $userRole->user_id = $user->id;
        $userRole->role = User::ROLE_USER;

        return $userRole->saveStrict();
    }
}
