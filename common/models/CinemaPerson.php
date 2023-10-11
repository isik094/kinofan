<?php

namespace common\models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="CinemaPerson",
 *      type="object",
 *       @OA\Property(property="id", type="integer", example="1", description="ID связки кино и персоны"),
 *       @OA\Property(property="cinema_id", type="integer", example="1", description="ID кино"),
 *       @OA\Property(property="person_id", type="integer", example="2", description="ID персоны"),
 *       @OA\Property(property="profession_key", type="string", example="DIRECTOR", description="Ключ названия профессии в кино персоны"),
 * )
 *
 * This is the model class for table "cinema_person".
 *
 * @property int $id
 * @property int|null $person_id
 * @property int|null $cinema_id
 * @property string|null $profession_key
 *
 * @property Cinema $cinema
 * @property Person $person
 */
class CinemaPerson extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cinema_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_id', 'cinema_id'], 'integer'],
            [['profession_key'], 'string', 'max' => 255],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'person_id' => 'Person ID',
            'cinema_id' => 'Cinema ID',
            'profession_key' => 'Profession Key',
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
     * Gets query for [[Person]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }
}
