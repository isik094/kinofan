<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "distribution".
 *
 * @property int $id
 * @property int|null $cinema_id
 * @property string|null $type
 * @property string|null $sub_type
 * @property int|null $date
 * @property int|null $re_release
 * @property int|null $country_id
 * @property int|null $company_id
 *
 * @property Cinema $cinema
 * @property Company $company
 * @property Country $country
 */
class Distribution extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'distribution';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cinema_id', 're_release', 'country_id', 'company_id', 'date'], 'integer'],
            [['type', 'sub_type'], 'string', 'max' => 255],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'type' => 'Type',
            'sub_type' => 'Sub Type',
            'date' => 'Date',
            're_release' => 'Re Release',
            'country_id' => 'Country ID',
            'company_id' => 'Company ID',
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
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
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
}
