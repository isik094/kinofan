<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CinemaGenre",
 *     type="object",
 *      @OA\Property(property="id", type="integer", example="1", description="ID связки кино и жанра"),
 *      @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *      @OA\Property(property="genre_id", type="integer", example="2", description="ID жанра"),
 * )
 *
 * This is the model class for table "cinema_genre".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property int|null $genre_id
 *
 * @property Cinema $cinema
 * @property Genre $genre
 */
class CinemaGenre extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'genre_id'], 'integer'],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
            [['genre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genre::className(), 'targetAttribute' => ['genre_id' => 'id']],
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
            'genre_id' => 'Genre ID',
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
     * Gets query for [[Genre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenre()
    {
        return $this->hasOne(Genre::className(), ['id' => 'genre_id']);
    }

    /**
     * @brief Создать запись фильма и его жанров
     * @param Cinema $cinema
     * @param Genre $genre
     * @return CinemaGenre|false
     */
    public static function create(Cinema $cinema, Genre $genre): bool|CinemaGenre
    {
        try {
            $cinemaGenre = new CinemaGenre();
            $cinemaGenre->cinema_id = $cinema->id;
            $cinemaGenre->genre_id = $genre->id;
            $cinemaGenre->saveStrict();

            return $cinemaGenre;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}
