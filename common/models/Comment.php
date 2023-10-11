<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *       schema="Comment",
 *       type="object",
 *        @OA\Property(property="id", type="integer", example="1", description="ID комментария"),
 *        @OA\Property(property="text", type="string", example="Крутой фильм", description="Текст комментария"),
 *        @OA\Property(property="created_at", type="integer", example="1696849581", description="Время создания в unixtime"),
 *        @OA\Property(property="name", type="string", example="Иван", description="Имя пользователя оставившего комментарий"),
 *        @OA\Property(property="surname", type="string", example="Иванов", description="Фамилия пользователя оставившего комментарий"),
 *        @OA\Property(property="children", type="array", @OA\Items(type="object")
 *      )
 * )
 *
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
    public const UNDER_CONSIDERATION = 0;
    /** @var int Одобрен */
    public const APPROVED = 1;
    /** @var int Значение комментария по умолчанию главного */
    public const DEFAULT_PARENT_ID = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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
    public function getCinema(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Cinema::className(), ['id' => 'cinema_id']);
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
