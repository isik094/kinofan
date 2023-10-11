<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *       schema="Company",
 *       type="object",
 *        @OA\Property(property="id", type="integer", example="1", description="ID компании"),
 *        @OA\Property(property="name", type="string", example="Universal", description="Название кинокомпании"),
 * )
 *
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
    public static function tableName(): string
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['name'], 'trim'],
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
     * Gets query for [[Distributions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDistributions(): \yii\db\ActiveQuery
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
