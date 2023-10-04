<?php

namespace common\models;

/**
 * This is the model class for table "cinema_selection".
 *
 * @property int $id
 * @property int $selection_id
 * @property int|null $cinema_id
 *
 * @property Cinema $cinema
 * @property Selection $selection
 */
class CinemaSelection extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_selection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['selection_id'], 'required'],
            [['selection_id', 'cinema_id'], 'integer'],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
            [['selection_id'], 'exist', 'skipOnError' => true, 'targetClass' => Selection::className(), 'targetAttribute' => ['selection_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'selection_id' => 'Selection ID',
            'cinema_id' => 'Cinema ID',
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
     * Gets query for [[Selection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSelection()
    {
        return $this->hasOne(Selection::className(), ['id' => 'selection_id']);
    }
}
