<?php

namespace common\models;

/**
 * This is the model class for table "user_hobbies".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $hobbies_id
 *
 * @property Hobbies $hobbies
 * @property User $user
 */
class UserHobbies extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_hobbies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'hobbies_id'], 'integer'],
            [['hobbies_id'], 'exist', 'skipOnError' => true, 'targetClass' => Hobbies::className(), 'targetAttribute' => ['hobbies_id' => 'id']],
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
            'hobbies_id' => 'Hobbies ID',
        ];
    }

    /**
     * Gets query for [[Hobbies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHobbies(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Hobbies::className(), ['id' => 'hobbies_id']);
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
