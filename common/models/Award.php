<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  schema="Award",
 *  type="object",
 *   @OA\Property(property="id", type="integer", example="1", description="ID награды"),
 *   @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *   @OA\Property(property="name", type="string", example="Матрица", description="Название кино на русском"),
 *   @OA\Property(property="win", type="integer", example="1", description="Победитель"),
 *   @OA\Property(property="image_url", type="string", example="https://kinofan.api/v1/file?uuid=rSST2uTsf53EZeMSYtDieghZ41yPLlfceiFKOqJQrMsar578Tjfh1xh2-vZxOAVs6u04P-I1hb8h5r35yHzrC5ouVBgPsVsHZrkYi-IOh7NGemYq3SKO6k_nD-B4Q1AgX5FkzdqU8QWpjKPfgjU0fw8s7EH0LTFxLW0GmheaIJHL4Aq9sruBW6zd1f0Q6nNE0s7da7TDzNJmSzlEebuY-d32SNrjbVEt6fHqXvlIGpVmNxhtQrvYJkqB5V7hiXC", description="Ссылка на картинку"),
 *   @OA\Property(property="nomination_name", type="string", example="Лучший звук", description="Название номинации"),
 *   @OA\Property(property="year", type="integer", example="2023", description="Год"),
 * )
 *
 * This is the model class for table "award".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property string|null $name
 * @property int|null $win
 * @property string|null $image_url
 * @property string|null $nomination_name
 * @property int|null $year
 *
 * @property Cinema $cinema
 * @property AwardPerson[] $awardPeople
 */
class Award extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'award';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'win', 'year'], 'integer'],
            [['name', 'image_url', 'nomination_name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'win' => 'Win',
            'image_url' => 'Image Url',
            'nomination_name' => 'Nomination Name',
            'year' => 'Year',
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
     * Gets query for [[AwardPeople]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAwardPeople()
    {
        return $this->hasMany(AwardPerson::className(), ['award_id' => 'id']);
    }
}
