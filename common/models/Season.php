<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property int|null $season_number
 * @property int|null $episode_number
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $synopsis
 * @property int|null $release_date
 *
 * @property Cinema $cinema
 */
class Season extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'season_number', 'episode_number', 'release_date'], 'integer'],
            [['name_ru', 'name_en', 'synopsis'], 'string', 'max' => 255],
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
            'season_number' => 'Season Number',
            'episode_number' => 'Episode Number',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'synopsis' => 'Synopsis',
            'release_date' => 'Release Date',
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
}
