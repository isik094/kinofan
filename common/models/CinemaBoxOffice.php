<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cinema_box_office".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property string|null $type
 * @property float|null $amount
 * @property string|null $symbol
 *
 * @property Cinema $cinema
 */
class CinemaBoxOffice extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_box_office';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id'], 'integer'],
            [['amount'], 'number'],
            [['type', 'symbol'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'amount' => 'Amount',
            'symbol' => 'Symbol',
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
