<?php

namespace common\models;

use Yii;

/**
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
