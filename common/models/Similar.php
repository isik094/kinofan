<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "similar".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property int|null $id_kp
 *
 * @property Cinema $cinema
 */
class Similar extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'similar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 'id_kp'], 'integer'],
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
            'id_kp' => 'Id Kp',
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
     * @brief Получить похожий фильм
     * @return Cinema|null
     */
    public function getSimilar(): ?Cinema
    {
        return Cinema::findOne(['id_kp' => $this->id_kp]);
    }
}
