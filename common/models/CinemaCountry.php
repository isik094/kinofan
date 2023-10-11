<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="CinemaCountry",
 *   type="object",
 *    @OA\Property(property="id", type="integer", example="1", description="ID связки страны и фильма"),
 *    @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *    @OA\Property(property="country_id", type="integer", example="2", description="ID страны"),
 * )
 *
 * This is the model class for table "cinema_country".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property int|null $country_id
 *
 * @property Cinema $cinema
 * @property Country $country
 */
class CinemaCountry extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'country_id'], 'integer'],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
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
            'country_id' => 'Country ID',
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
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @brief Создать запись
     * @param Cinema $cinema
     * @param Country $country
     * @return CinemaCountry|bool
     */
    public static function create(Cinema $cinema, Country $country): CinemaCountry|bool
    {
        try {
            $cinemaCountry = new CinemaCountry();
            $cinemaCountry->cinema_id = $cinema->id;
            $cinemaCountry->country_id = $country->id;
            $cinemaCountry->saveStrict();

            return $cinemaCountry;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}
