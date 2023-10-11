<?php

namespace api\modules\v1\models;

use Yii;
use OpenApi\Annotations as OA;
use common\models\UserGenreCinema;
use common\models\UserHobbies;
use common\base\Model;
use common\models\Country;
use common\models\Genre;
use common\models\Hobbies;
use common\models\Profile;
use common\models\User;
use common\models\UserCountryCinema;

/**
 * @OA\Schema(
 *        schema="PersonalizationForm",
 *         @OA\Property(property="sex", type="string", example="male", description="Пол", enum={"male", "female"}),
 *         @OA\Property(property="birthday", type="integer", example="5454545454", description="Дата рождения в unixtime"),
 *         @OA\Property(property="country_id", type="integer", example="1", description="ID старны"),
 *         @OA\Property(property="genre_ids", type="array", description="ID жанров массивом", @OA\Items(anyOf={@OA\Schema(type="integer", example="1")})),
 *         @OA\Property(property="hobbies_ids", type="array", description="ID увлечений массивом", @OA\Items(anyOf={@OA\Schema(type="integer", example="2")})),
 * )
 */
class PersonalizationForm extends Model
{
    /**
     * @var User
     */
    public User $user;
    /**
     * @var string
     */
    public $sex;
    /**
     * @var string
     */
    public $birthday;
    /**
     * @var int
     */
    public $country_id;
    /**
     * @var array
     */
    public $genre_ids;
    /**
     * @var array
     */
    public $hobbies_ids;

    public function rules(): array
    {
        return [
            [['sex', 'birthday'], 'string', 'max' => 255],
            [['country_id'], 'integer'],
            [['genre_ids', 'hobbies_ids'], 'each', 'rule' => ['integer']],
            [['genre_ids'], 'genreValidate'],
            [['hobbies_ids'], 'hobbiesValidate'],
            ['sex', 'in', 'range' => [Profile::MALE, Profile::FEMALE]],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'sex' => 'Пол',
            'birthday' => 'День рождения',
            'country_id' => 'ID страны',
            'genre_ids' => 'ID жанров',
            'hobbies_ids' => 'ID увлечений',
        ];
    }

    /**
     * @brief Валидация на существование жанра
     * @param $attribute
     * @return void
     */
    public function genreValidate($attribute): void
    {
        $doesNotExist = false;
        foreach ($this->genre_ids as $genre_id) {
            if (!Genre::findOne($genre_id)) {
                $doesNotExist = true;
            }
        }

        if ($doesNotExist === true) {
            $this->addError($attribute, 'В массиве жанры есть не существующая запись');
        }
    }

    /**
     * @brief Валидация на существование увлечения
     * @param $attribute
     * @return void
     */
    public function hobbiesValidate($attribute): void
    {
        $doesNotExist = false;
        foreach ($this->hobbies_ids as $hobbies_id) {
            if (!Hobbies::findOne($hobbies_id)) {
                $doesNotExist = true;
            }
        }

        if ($doesNotExist === true) {
            $this->addError($attribute, 'В массиве жанры есть не существующая запись');
        }
    }

    /**
     * @brief Персонализация
     * @return User|null
     * @throws \yii\db\Exception
     */
    public function updatePersonalization(): ?User
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $profile = $this->user->profile;
            $profile->sex = $this->sex;
            $profile->birthday = strtotime($this->birthday);
            $profile->saveStrict();

            $this->createUserCountry();
            $this->createUserGenre();
            $this->createUserHobbies();
            $transaction->commit();

            return $this->user;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @brief Создать запись о предпочтительной старне кино пользователя
     * @return bool|null
     * @throws \Exception
     */
    public function createUserCountry(): ?bool
    {
        if (
            !UserCountryCinema::findOne([
                'user_id' => $this->user->id,
                'country_id' => $this->country_id
            ])
        ) {
            $model = new UserCountryCinema();
            $model->user_id = $this->user->id;
            $model->country_id = $this->country_id;

            return $model->saveStrict();
        }

        return null;
    }

    /**
     * @brief Создать запись о предпочтительном жанре пользователя
     * @return bool
     * @throws \Exception
     */
    public function createUserGenre(): bool
    {
        try {
            foreach ($this->genre_ids as $genre_id) {
                if (
                    UserGenreCinema::findOne([
                    'user_id' => $this->user->id,
                    'genre_id' => $genre_id])
                ) {
                    continue;
                }

                $model = new UserGenreCinema();
                $model->user_id = $this->user->id;
                $model->genre_id = $genre_id;
                $model->saveStrict();
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @brief Создать запись о увлечении пользователя
     * @return bool
     * @throws \Exception
     */
    public function createUserHobbies(): bool
    {
        try {
            foreach ($this->hobbies_ids as $hobbies_id) {
                if (
                    UserHobbies::findOne([
                    'user_id' => $this->user->id,
                    'hobbies_id' => $hobbies_id])
                ) {
                    continue;
                }
                $model = new UserHobbies();
                $model->user_id = $this->user->id;
                $model->hobbies_id = $hobbies_id;
                $model->saveStrict();
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
