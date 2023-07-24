<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "genre".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property CinemaGenre[] $cinemaGenres
 * @property UserGenreCinema[] $userGenreCinemas
 */
class Genre extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[CinemaGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaGenres()
    {
        return $this->hasMany(CinemaGenre::className(), ['genre_id' => 'id']);
    }

    /**
     * Gets query for [[UserGenreCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGenreCinemas()
    {
        return $this->hasMany(UserGenreCinema::className(), ['genre_id' => 'id']);
    }
}
