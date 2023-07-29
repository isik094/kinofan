<?php

namespace common\models;

use Yii;

/**
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
