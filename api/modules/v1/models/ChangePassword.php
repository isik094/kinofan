<?php

namespace api\modules\v1\models;

use Yii;
use OpenApi\Annotations as OA;
use common\models\User;
use common\base\Model;

/**
 * @OA\Schema(
 *        schema="ChangePassword",
 *        required={"currentPassword", "newPassword"},
 *         @OA\Property(property="currentPassword", type="string", example="123456", description="Текущий пароль пользователя"),
 *         @OA\Property(property="newPassword", type="string", example="654321", description="Новый пароль пользователя"),
 * )
 */
class ChangePassword extends Model
{
    /**
     * @var User
     */
    public User $user;
    /**
     * @var string
     */
    public $currentPassword;
    /**
     * @var string
     */
    public $newPassword;

    public function rules(): array
    {
        return [
            [['currentPassword', 'newPassword'], 'required'],
            ['newPassword', 'string', 'max' => 255, 'min' => Yii::$app->params['user.passwordMinLength']],
            ['currentPassword', 'validatePassword'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'currentPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
        ];
    }

    /**
     * @brief Валидация пароля
     * @param $attribute
     * @return void
     */
    public function validatePassword($attribute): void
    {
        if (!$this->user->validatePassword($this->currentPassword)) {
            $this->addError($attribute, 'Текущий пароль неверный');
        }
    }

    /**
     * @brief Изменить пароль
     * @return bool|null
     * @throws \Exception
     */
    public function changePassword(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $this->user->setPassword($this->newPassword);
        $this->user->generateAuthKey();

        return $this->user->saveStrict();
    }
}