<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *    schema="CinemaFacts",
 *    type="object",
 *     @OA\Property(property="id", type="integer", example="1", description="ID факта"),
 *     @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *     @OA\Property(property="text", type="string", example="Текст факта", description="Текст или ошибка фильма"),
 *     @OA\Property(property="type", type="string", example="fact", description="Обозночение факта или ошибки", enum={"fact", "blooper"}),
 *     @OA\Property(property="spoiler", type="integer", example="fact", description="Наличие спойлера", enum={"0", "1"}),
 * )
 *
 * This is the model class for table "cinema_facts".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property string|null $text
 * @property string|null $type
 * @property int|null $spoiler
 *
 * @property Cinema $cinema
 */
class CinemaFacts extends \common\base\ActiveRecord
{
    public const FACT = 'fact';
    public const BLOOPER = 'blooper';

    public static array $typeFact = [
        self::FACT => 'Интересный факт о фильме',
        self::BLOOPER => 'Ошибка в фильме',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_facts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'spoiler'], 'integer'],
            [['text'], 'string', 'max' => 600],
            [['type'], 'string', 'max' => 255],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
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
            'text' => 'Text',
            'type' => 'Type',
            'spoiler' => 'Spoiler',
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
}
