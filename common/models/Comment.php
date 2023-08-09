<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property int|null $user_id
 * @property int $parent_id
 * @property string|null $text
 * @property int|null $created_at
 * @property int|null $status
 *
 * @property Cinema $cinema
 * @property User $user
 */
class Comment extends \common\base\ActiveRecord
{
    /** @var int На рассмотрении */
    const UNDER_CONSIDERATION = 0;
    /** @var int Одобрен */
    const APPROVED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'user_id', 'parent_id', 'created_at', 'status'], 'integer'],
            [['parent_id'], 'required'],
            [['text'], 'string', 'max' => 600],
            [['status'], 'in', 'range' => [self::UNDER_CONSIDERATION, self::APPROVED]],
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
            'cinema_id' => 'Cinema ID',
            'user_id' => 'User ID',
            'parent_id' => 'Parent ID',
            'text' => 'Text',
            'created_at' => 'Created At',
            'status' => 'Status',
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
