<?php

namespace common\models;

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
    public static function tableName(): string
    {
        return 'genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name'], 'trim'],
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
     * Gets query for [[CinemaGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaGenres(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CinemaGenre::className(), ['genre_id' => 'id']);
    }

    /**
     * Gets query for [[UserGenreCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGenreCinemas(): \yii\db\ActiveQuery
    {
        return $this->hasMany(UserGenreCinema::className(), ['genre_id' => 'id']);
    }

    /**
     * @brief Создать запись
     * @param string $name
     * @return Genre|false
     */
    public static function create(string $name): Genre|bool
    {
        try {
            $genre = new Genre();
            $genre->name = $name;
            $genre->saveStrict();

            return $genre;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}
