<?php

namespace api\modules\v1\models;

use Yii;
use api\components\Code;
use common\base\Model;
use common\models\User;
use common\models\ConfirmEmailCode;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *       schema="SendCodeForm",
 *       required={"email"},
 *        @OA\Property(property="email", type="string", example="isik@yandex.ru", description="Электронная почта пользователя"),
 * )
 */
class SendCodeForm extends Model
{
    /**
     * @var string
     */
    public $email;

    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'emailValidate'],
            ['email', 'codeValidate'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Электронная почта',
        ];
    }

    /**
     * @brief Проверка на существование пользователя с такой почтой
     * @param $attribute
     * @return void
     */
    public function emailValidate($attribute): void
    {
        $user = User::findOne(['email' => $this->email]);

        if ($user === null) {
            $this->addError($attribute, 'Нет такого зарегистрированного пользователя');
        }
    }

    /**
     * @brief Валидация на переодичность запроса кода для почты
     * @param $attribute
     * @return void
     */
    public function codeValidate($attribute): void
    {
        $lastCode = Code::getLastCode($this->email);

        /** @var ConfirmEmailCode|null $lastCode */
        if ($lastCode?->created_at > time() - Code::PAUSE_BETWEEN_QUERY) {
            $seconds = $lastCode?->created_at - (time() - Code::PAUSE_BETWEEN_QUERY);

            $this->addError(
                $attribute,
                "Вы недавно запрашивали код. Повторно запросить можно будет через: {$seconds} секунд"
            );
        }
    }

    /**
     * @brief Создать код и отправить на почту
     * @return ConfirmEmailCode|null
     * @throws \Exception
     */
    public function sendCode(): ?ConfirmEmailCode
    {
        try {
            if (!$this->validate()) {
                return null;
            }

            $code = Code::getCode();
            $user = User::findOne(['email' => $this->email]);

            $model = new ConfirmEmailCode();
            $model->user_id = $user->id;
            $model->email = $this->email;
            $model->code = $code;
            $model->created_at = time();
            $model->saveStrict();
            $this->sendEmail($user, $code);

            return $model;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Sends confirmation email to user
     * @return bool whether the email was sent
     */
    public function sendEmail(User $user, string $code): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordReset-html', 'text' => 'passwordReset-text'],
                ['user' => $user, 'code' => $code],
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($user->email)
            ->setSubject('Восстановление пароля ' . Yii::$app->name)
            ->send();
    }
}