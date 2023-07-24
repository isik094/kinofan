<?php

namespace common\models;

use Yii;

/**
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
    public static function tableName()
    {
        return 'country';
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
     * Gets query for [[CinemaCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaCountries()
    {
        return $this->hasMany(CinemaCountry::className(), ['country_id' => 'id']);
    }

    /**
     * Gets query for [[Distributions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistributions()
    {
        return $this->hasMany(Distribution::className(), ['country_id' => 'id']);
    }

    /**
     * Gets query for [[UserCountryCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCountryCinemas()
    {
        return $this->hasMany(UserCountryCinema::className(), ['country_id' => 'id']);
    }
}
