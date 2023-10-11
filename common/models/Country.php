<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *       schema="Country",
 *       type="object",
 *        @OA\Property(property="id", type="integer", example="1", description="ID просмотренного фильма пользователя"),
 *        @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *        @OA\Property(property="user_id", type="integer", example="2", description="ID пользователя"),
 * )
 *
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property CinemaCountry[] $cinemaCountries
 * @property Distribution[] $distributions
 * @property UserCountryCinema[] $userCountryCinemas
 */
class Country extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'country';
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
     * Gets query for [[CinemaCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaCountries(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CinemaCountry::className(), ['country_id' => 'id']);
    }

    /**
     * Gets query for [[Distributions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistributions(): \yii\db\ActiveQuery
    {
        return $this->hasMany(Distribution::className(), ['country_id' => 'id']);
    }

    /**
     * Gets query for [[UserCountryCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCountryCinemas(): \yii\db\ActiveQuery
    {
        return $this->hasMany(UserCountryCinema::className(), ['country_id' => 'id']);
    }

    /**
     * @param string $name
     * @return Country|bool
     */
    public static function create(string $name): Country|bool
    {
        try {
            $country = new Country();
            $country->name = $name;
            $country->saveStrict();

            return $country;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}
