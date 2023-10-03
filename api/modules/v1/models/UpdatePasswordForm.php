<?php

namespace api\modules\v1\models;

use common\models\ConfirmEmailCode;
use Yii;
use common\models\User;
use common\base\Model;
use api\modules\components\Code;

class UpdatePasswordForm extends Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $code;
    /**
     * @var string
     */
    public $password;

    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'string'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'emailValidate'],

            ['code', 'required'],
            ['code', 'trim'],
            ['code', 'string', 'length' => 6],
            ['code', 'codeValidate'],

            ['password', 'required'],
            ['password', 'trim'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'code' => 'Код',
            'password' => 'Пароль',
            'email' => 'Электронная почта',
        ];
    }

    public function emailValidate($attribute): void
    {
        $user = User::findOne(['email' => $this->email]);

        if ($user === null) {
            $this->addError($attribute, 'Нет такого пользователя');
        }
    }

    /**
     * @throws \Exception
     */
    public function codeValidate($attribute): void
    {
        $code = Code::getActualCode($this->email, $this->code);

        /** @var ConfirmEmailCode $code */
        if ($code) {
            $code->accepted_at = time();
            $code->saveStrict();
        } else {
            $this->addError($attribute, 'Недействительный код. Возможно устарел');
        }
    }

    /**
     * @brief Обновить пароль
     * @return User|null
     * @throws \Exception
     */
    public function updatePassword(): ?User
    {
        try {
            if (!$this->validate()) {
                return null;
            }

            $user = User::findOne(['email' => $this->email]);
            $user->setPassword($this->password);
            $user->saveStrict();

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
