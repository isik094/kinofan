<?php

namespace common\models;

use Yii;

/**
 * @OA\Schema(
 *  schema="Cinema",
 *  type="object",
 *  @OA\Property(property="id", type="integer", example="1", description="ID кино"),
 *  @OA\Property(property="id_kp", type="integer", example="1", description="ID кинопоиска"),
 *  @OA\Property(property="name_ru", type="string", example="Матрица", description="Название кино на русском"),
 *  @OA\Property(property="name_original", type="string", example="Matrix", description="Оригинальное название"),
 *  @OA\Property(property="poster_url", type="string", example="https://kinofan.api/v1/file?uuid=rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на постер"),
 *  @OA\Property(property="poster_url_preview", type="string", example="https://kinofan.api/v1/file?uuid=rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на превью"),
 *  @OA\Property(property="rating_kinopoisk", type="float", example="8.2", description="Рейтинг кино на кинопоиске"),
 *  @OA\Property(property="year", type="integer", example="2023", description="Год"),
 *  @OA\Property(property="film_length", type="integer", example="120", description="Продолжительность"),
 *  @OA\Property(property="slogan", type="string", example="Добро пожаловать в реальный мир", description="Слоган"),
 *  @OA\Property(property="description", type="string", example="Жизнь Томаса Андерсона разделена на две части: днём он-самый обычный офисный работник, получающий нагоняи от начальства, а ночью превращается в хакера по имени Нео, и нет места в сети, куда он бы не смог проникнуть. Но однажды всё меняется. Томас узнаёт ужасающую правду о реальности.", description="Описание"),
 *  @OA\Property(property="type", type="string", example="movie", description="Тип", enum={movie, series, cartoon, anime, tv_show}),
 *  @OA\Property(property="rating_mpaa", type="string", example="r", description="Рейтинг MPAA"),
 *  @OA\Property(property="rating_age_limits", type="string", example="age16", description="Возрастной лимит"),
 *  @OA\Property(property="start_year", type="integer", example="1691150253", description="Старт"),
 *  @OA\Property(property="end_year", type="integer", example="1691150253", description="Конец"),
 *  @OA\Property(property="serial", type="integer", example="0", description="Сериал"),
 *  @OA\Property(property="completed", type="integer", example="0", description="Окончен или нет (для сериалов)"),
 *  @OA\Property(property="completed", type="integer", example="0", description="Окончен или нет (для сериалов)"),
 *  @OA\Property(property="created_at", type="integer", example="1691150253", description="Время создания"),
 *  @OA\Property(property="premiere_ru", type="integer", example="1691150253", description="Время премьеры в РФ"),
 *  @OA\Property(property="release_date", type="integer", example="1691150253", description="Время цифрового релиза"),
 *  @OA\Property(property="rating_imdb", type="float", example="1691150253", description="Рейтинг IMDB"),
 * )
 *
 * This is the model class for table "cinema".
 *
 * @property int $id
 * @property int|null $id_kp
 * @property string|null $name_ru
 * @property string|null $name_original
 * @property string|null $poster_url
 * @property string|null $poster_url_preview
 * @property float|null $rating_kinopoisk
 * @property int|null $year
 * @property int|null $film_length
 * @property string|null $slogan
 * @property string|null $description
 * @property string|null $type
 * @property string|null $rating_mpaa
 * @property string|null $rating_age_limits
 * @property int|null $start_year
 * @property int|null $end_year
 * @property int|null $serial
 * @property int|null $completed
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int|null $premiere_ru
 * @property int|null $release_date
 * @property float|null $rating_imdb
 *
 * @property Award[] $awards
 * @property CinemaBoxOffice[] $cinemaBoxOffices
 * @property CinemaCountry[] $cinemaCountries
 * @property CinemaFacts[] $cinemaFacts
 * @property CinemaGenre[] $cinemaGenres
 * @property CinemaPerson[] $cinemaPeople
 * @property Comment[] $comments
 * @property CompilationCinema[] $compilationCinemas
 * @property Distribution[] $distributions
 * @property Favorites[] $favorites
 * @property Image[] $images
 * @property PersonCinema[] $personCinemas
 * @property Product[] $products
 * @property Season[] $seasons
 * @property SequelAndPrequel[] $sequelAndPrequels
 * @property Similar[] $similars
 * @property Video[] $videos
 */
class Cinema extends \common\base\ActiveRecord
{
    public const MOVIE = 'movie';
    public const SERIES = 'series';
    public const CARTOON = 'cartoon';
    public const ANIME = 'anime';
    public const TV_SHOW = 'tv_show';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kp', 'year', 'film_length', 'start_year', 'end_year', 'serial', 'completed', 'created_at', 'updated_at', 'deleted_at', 'premiere_ru', 'release_date'], 'integer'],
            [['rating_kinopoisk', 'rating_imdb'], 'number'],
            [['description'], 'string'],
            [['name_ru', 'name_original', 'poster_url', 'poster_url_preview', 'slogan', 'type', 'rating_mpaa', 'rating_age_limits'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kp' => 'Id Kp',
            'name_ru' => 'Name Ru',
            'name_original' => 'Name Original',
            'poster_url' => 'Poster Url',
            'poster_url_preview' => 'Poster Url Preview',
            'rating_kinopoisk' => 'Rating Kinopoisk',
            'year' => 'Year',
            'film_length' => 'Film Length',
            'slogan' => 'Slogan',
            'description' => 'Description',
            'type' => 'Type',
            'rating_mpaa' => 'Rating Mpaa',
            'rating_age_limits' => 'Rating Age Limits',
            'start_year' => 'Start Year',
            'end_year' => 'End Year',
            'serial' => 'Serial',
            'completed' => 'Completed',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'premiere_ru' => 'Premiere Ru',
            'release_date' => 'Release Date',
            'rating_imdb' => 'Rating Imdb',
        ];
    }

//    public static function defineTypeCinema(\stdClass $cinema)
//    {
//        if (array_search('аниме', array_column($cinema->genres, 'genre')) && $cinema->type !== 'TV_SERIES') {
//            return self::ANIME;
//        }
//
//        if (array_search('мультфильм', array_column($cinema->genres, 'genre'))  && $cinema->type !== 'TV_SERIES') {
//            return self::CARTOON;
//        }
//    }

    /**
     * Gets query for [[Awards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[CinemaBoxOffices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaBoxOffices()
    {
        return $this->hasMany(CinemaBoxOffice::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[CinemaCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaCountries()
    {
        return $this->hasMany(CinemaCountry::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[CinemaFacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaFacts()
    {
        return $this->hasMany(CinemaFacts::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[CinemaGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaGenres()
    {
        return $this->hasMany(CinemaGenre::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[CinemaPeople]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaPeople()
    {
        return $this->hasMany(CinemaPerson::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[CompilationCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompilationCinemas()
    {
        return $this->hasMany(CompilationCinema::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Distributions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistributions()
    {
        return $this->hasMany(Distribution::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorites::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[PersonCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonCinemas()
    {
        return $this->hasMany(PersonCinema::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Seasons]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(Season::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[SequelAndPrequels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSequelAndPrequels()
    {
        return $this->hasMany(SequelAndPrequel::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Similars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSimilars()
    {
        return $this->hasMany(Similar::className(), ['cinema_id' => 'id']);
    }

    /**
     * Gets query for [[Videos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['cinema_id' => 'id']);
    }
}
