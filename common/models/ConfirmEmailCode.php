<?php

namespace common\models;

/**
 * This is the model class for table "confirm_email_code".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $email
 * @property string|null $code
 * @property int|null $created_at
 * @property int|null $accepted_at
 *
 * @property User $user
 */
class ConfirmEmailCode extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'confirm_email_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'accepted_at'], 'integer'],
            [['email', 'code'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'email' => 'Email',
            'code' => 'Code',
            'created_at' => 'Created At',
            'accepted_at' => 'Accepted At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
