<?php

namespace common\models;

use yii\base\Model;
use yii\base\Exception;

/**
 *
 * @OA\Schema(
 *     schema="LoginForm",
 *     required={"username", "password"},
 *     @OA\Property(property="username", type="string", example="isik@yandex.ru", description="Электронная почта пользователя"),
 *     @OA\Property(property="password", type="string", example="qwerty", description="Пароль"),
 * )
 *
 * Login form
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;
    /**
     * @var boolean
     */
    public $rememberMe;

    /**
     * @var User $_user
     */
    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Электронная почта',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильное имя пользователя или пароль.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return User|null whether the user is logged in successfully
     * @throws Exception
     * @throws \Exception
     */
    public function login()
    {
        if ($this->validate() && $this->getUser()->generateToken()) {
            return $this->getUser();
        }

        return null;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
