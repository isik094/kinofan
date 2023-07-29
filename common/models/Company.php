<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property Distribution[] $distributions
 */
class Company extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name'], 'trim'],
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
     * Gets query for [[Distributions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistributions()
    {
        return $this->hasMany(Distribution::className(), ['company_id' => 'id']);
    }

    /**
     * @brief Создать название компании
     * @param string $name
     * @return Company|bool
     */
    public static function create(string $name): Company|bool
    {
        try {
            $company = new Company();
            $company->name = $name;
            $company->saveStrict();

            return $company;
        } catch (\Exception $e) {
            ErrorLog::createLog($e);
            return false;
        }
    }
}
