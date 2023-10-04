<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "selection".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property CinemaSelection[] $cinemaSelections
 */
class Selection extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'selection';
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
     * Gets query for [[CinemaSelections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaSelections()
    {
        return $this->hasMany(CinemaSelection::className(), ['selection_id' => 'id']);
    }
}
