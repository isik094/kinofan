<?php

namespace api\modules\v1\models;

use Yii;
use common\base\Model;
use common\models\User;

class ProfileUpdateForm extends Model
{
    /**
     * @var User
     */
    public User $user;
    /**
     * @var string
     */
    public $surname;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $patronymic;
    /**
     * @var string
     */
    public $vk;
    /**
     * @var string
     */
    public $telegram;
    /**
     * @var string
     */
    public $email;

    public function rules(): array
    {
        return [
            [['surname', 'name', 'patronymic', 'vk', 'telegram', 'email'], 'string', 'max' => 255],
            [['surname', 'name', 'patronymic', 'vk', 'telegram', 'email'], 'trim'],

            ['email', 'email'],
            ['email', 'existValidate'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'vk' => 'Вк',
            'telegram' => 'Телеграмм',
            'email' => 'Электронная почта',
        ];
    }

    /**
     * @brief Валидация на существование данной почты
     * @param $attribute
     * @return void
     */
    public function existValidate($attribute): void
    {
        $user = User::findOne(['and',
            ['<>', 'id', $this->user->id],
            ['email' => $this->email]
        ]);

        if ($user) {
            $this->addError($attribute, 'Электронная почта уже используется');
        }
    }

    /**
     * @brief Обновить профиль
     * @return User|null
     * @throws \yii\db\Exception
     */
    public function update(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->user->username = $this->email;
            $this->user->email = $this->email;
            $this->user->saveStrict();


            $profile = $this->user->profile;
            $profile->surname = $this->surname;
            $profile->name = $this->name;
            $profile->patronymic = $this->patronymic;
            $profile->vk = $this->vk;
            $profile->telegram = $this->telegram;
            $profile->saveStrict();
            $transaction->commit();

            if ($this->email) {
                $this->sendEmail();
            }

            return $this->user;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Sends confirmation email to user
     * @return bool whether the email was sent
     */
    public function sendEmail(): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $this->user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
