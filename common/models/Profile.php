<?php

namespace common\models;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $surname
 * @property string|null $name
 * @property string|null $patronymic
 * @property string|null $vk
 * @property string|null $telegram
 * @property string|null $sex
 * @property int|null $birthday
 *
 * @property User $user
 */
class Profile extends \common\base\ActiveRecord
{
    public const MALE = 'male';
    public const FEMALE = 'FEMALE';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'birthday'], 'integer'],
            [['surname', 'name', 'patronymic', 'vk', 'telegram', 'sex'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'surname' => 'Surname',
            'name' => 'Name',
            'patronymic' => 'Patronymic',
            'vk' => 'Vk',
            'telegram' => 'Telegram',
            'sex' => 'Sex',
            'birthday' => 'Birthday',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
