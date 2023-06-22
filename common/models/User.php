<?php
namespace common\models;

use Yii;
use api\models\UserRole;
use common\base\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example="1", description="ID пользователя"),
 *     @OA\Property(property="username", type="email", example="qwe@gmail.com", description="Никнейм пользователя"),
 * )
 *
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $access_token
 *
 * @property UserRole[] $userRoles
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /** @var array|string[] */
    public static array $userStatus = [
        self::STATUS_DELETED => 'Удален',
        self::STATUS_INACTIVE => 'Не активен',
        self::STATUS_ACTIVE => 'Активен',
    ];

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /** @var array|string[]  */
    public static array $userRoles = [
        self::ROLE_ADMIN => 'Администратор',
        self::ROLE_USER => 'Пользователь',
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => array_keys(self::$userStatus)],
        ];
    }

    /**
     * @brief Очистить все токены пользователя при смене пароля
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes) {
        if (array_key_exists('password_hash', $changedAttributes)) {
            UserRefreshTokens::deleteAll(['user_id' => $this->id]);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @brief Получить все роли пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @brief Получить пользователя по токену
     * @param $token
     * @param $type
     * @return User
     */
    public static function findIdentityByAccessToken($token, $type = null): User
    {
        return static::findOne(['id' => (int)$token->getClaim('uid'), 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return User
     */
    public static function getCurrent()
    {
        return Yii::$app->user->identity;
    }

    /**
     * @brief Генерация токена для api
     * @return bool
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    public function generateToken()
    {
        if (!$this->access_token || self::tokenIsExpired($this->access_token)) {
            $this->access_token = time() . '_' . Yii::$app->security->generateRandomString(100);
            return $this->saveStrict();
        }

        return true;
    }

    /**
     * @brief Проверка на то, что токен устарел
     * @param $token
     * @return bool
     */
    private static function tokenIsExpired($token)
    {
        $tokenExplode = explode('_', $token);
        return strtotime(date('Y-m-d 23:59:59', (int)$tokenExplode[0])) < time() - 86400;
    }
}
