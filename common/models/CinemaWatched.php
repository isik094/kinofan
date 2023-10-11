<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="CinemaWatched",
 *      type="object",
 *       @OA\Property(property="id", type="integer", example="1", description="ID просмотренного фильма пользователя"),
 *       @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *       @OA\Property(property="user_id", type="integer", example="2", description="ID пользователя"),
 * )
 *
 * This is the model class for table "cinema_watched".
 *
 * @property int $id
 * @property int $user_id
 * @property int $cinema_id
 *
 * @property Cinema $cinema
 * @property User $user
 */
class CinemaWatched extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_watched';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'cinema_id'], 'required'],
            [['user_id', 'cinema_id'], 'integer'],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
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
            'cinema_id' => 'Cinema ID',
        ];
    }

    /**
     * Gets query for [[Cinema]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinema()
    {
        return $this->hasOne(Cinema::className(), ['id' => 'cinema_id']);
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
