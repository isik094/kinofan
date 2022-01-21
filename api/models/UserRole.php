<?php
namespace api\models;


use common\base\ActiveRecord;
use common\models\ErrorLog;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * Class UserRole
 * @package common\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $role
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserRole extends ActiveRecord
{

    /**
     * @var User $_user
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['role'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'role' => 'Роль',
        ];
    }

    /**
     * @brief Получить пользователя
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === null) {
            return $this->_user = User::findOne($this->user_id);
        }

        return $this->_user;
    }
}