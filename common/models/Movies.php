<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "movies".
 *
 * @property int $id
 * @property int|null $category
 * @property string|null $title_rus
 * @property string|null $title_eng
 * @property string|null $short_description
 * @property string|null $production_year
 * @property string|null $tagline
 * @property string|null $age
 * @property string|null $rating_mpaa
 * @property string|null $rating_mpaa_designation
 * @property string|null $time
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $kp_id
 */
class Movies extends \common\base\ActiveRecord
{
    const FILM = 1;
    const SERIES = 2;
    const CARTOON = 3;
    const ANIME = 4;
    const TV_SHOW = 5;

    public static array $moviesType = [
        self::FILM => 'Фильм',
        self::SERIES => 'Сериал',
        self::CARTOON => 'Мультфильм',
        self::ANIME => 'Аниме',
        self::TV_SHOW => 'ТВ-шоу',
    ];

    const PRODUCER = 1;
    const SCENARIO = 2;
    const DIRECTOR = 3;
    const OPERATOR = 4;
    const COMPOSER = 5;
    const ARTIST = 6;
    const MOUNTING = 7;
    const ACTOR = 8;

    public static array $listParticipants = [
        self::PRODUCER => 'Режиссер',
        self::SCENARIO => 'Сценарий',
        self::DIRECTOR => 'Продюсер',
        self::OPERATOR => 'Оператор',
        self::COMPOSER => 'Композитор',
        self::ARTIST => 'Художник',
        self::MOUNTING => 'Монтаж',
        self::ACTOR => 'Актер',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'movies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'created_at', 'kp_id'], 'integer'],
            [['description'], 'string'],
            [['title_rus', 'title_eng', 'production_year', 'tagline', 'age', 'rating_mpaa', 'rating_mpaa_designation', 'time'], 'string', 'max' => 255],
            [['short_description'], 'string', 'max' => 455],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Категория',
            'title_rus' => 'Название (русское)',
            'title_eng' => 'Название (Английское)',
            'short_description' => 'Краткое описание',
            'production_year' => 'Год производства',
            'tagline' => 'Слоган',
            'age' => 'Возраст',
            'rating_mpaa' => 'Рейтинг MPAA',
            'rating_mpaa_designation' => 'Рейтинг MPAA обозначение',
            'time' => 'Время',
            'description' => 'Описание',
            'created_at' => 'Время создания',
            'kp_id' => 'ID кинопоиска',
        ];
    }
}
