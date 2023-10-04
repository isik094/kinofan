<?php

namespace common\models;

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
    public static function tableName(): string
    {
        return 'selection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
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
     * Gets query for [[CinemaSelections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaSelections(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CinemaSelection::className(), ['selection_id' => 'id']);
    }
}
