<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "award_person".
 *
 * @property int $id
 * @property int $award_id
 * @property int $person_id
 * @property int|null $age
 * @property string|null $profession
 *
 * @property Award $award
 * @property Person $person
 */
class AwardPerson extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'award_person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['award_id', 'person_id'], 'required'],
            [['award_id', 'person_id', 'age'], 'integer'],
            [['profession'], 'string', 'max' => 400],
            [['award_id'], 'exist', 'skipOnError' => true, 'targetClass' => Award::className(), 'targetAttribute' => ['award_id' => 'id']],
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
            'award_id' => 'Award ID',
            'person_id' => 'Person ID',
            'age' => 'Age',
            'profession' => 'Profession',
        ];
    }

    /**
     * Gets query for [[Award]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAward()
    {
        return $this->hasOne(Award::className(), ['id' => 'award_id']);
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
