<?php

namespace common\models;

/**
 * This is the model class for table "hobbies".
 *
 * @property int $id
 * @property string $name
 *
 * @property UserHobbies[] $userHobbies
 */
class Hobbies extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'hobbies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[UserHobbies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserHobbies(): \yii\db\ActiveQuery
    {
        return $this->hasMany(UserHobbies::className(), ['hobbies_id' => 'id']);
    }
}
