<?php

namespace common\models;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property int $person_kp_id
 * @property string|null $web_url
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $sex
 * @property string|null $poster_url
 * @property int|null $growth
 * @property int|null $birthday
 * @property int|null $death
 * @property int|null $age
 * @property string|null $birthplace
 * @property string|null $deathplace
 * @property int|null $has_awards
 * @property string|null $profession
 *
 * @property AwardPerson[] $awardPeople
 * @property CinemaPerson[] $cinemaPeople
 * @property PersonCinema[] $personCinemas
 * @property PersonFact[] $personFacts
 * @property Spouse[] $spouses
 * @property Spouse[] $spouses0
 */
class Person extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_kp_id'], 'required'],
            [['person_kp_id', 'growth', 'age', 'has_awards', 'birthday', 'death'], 'integer'],
            [['web_url', 'name_ru', 'name_en', 'sex', 'poster_url', 'birthplace', 'deathplace', 'profession'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'person_kp_id' => 'Person Kp ID',
            'web_url' => 'Web Url',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'sex' => 'Sex',
            'poster_url' => 'Poster Url',
            'growth' => 'Growth',
            'birthday' => 'Birthday',
            'death' => 'Death',
            'age' => 'Age',
            'birthplace' => 'Birthplace',
            'deathplace' => 'Deathplace',
            'has_awards' => 'Has Awards',
            'profession' => 'Profession',
        ];
    }

    /**
     * Gets query for [[AwardPeople]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAwardPeople()
    {
        return $this->hasMany(AwardPerson::className(), ['person_id' => 'id']);
    }

    /**
     * Gets query for [[CinemaPeople]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCinemaPeople()
    {
        return $this->hasMany(CinemaPerson::className(), ['person_id' => 'id']);
    }

    /**
     * Gets query for [[PersonCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonCinemas()
    {
        return $this->hasMany(PersonCinema::className(), ['person_id' => 'id']);
    }

    /**
     * Gets query for [[PersonFacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersonFacts()
    {
        return $this->hasMany(PersonFact::className(), ['person_id' => 'id']);
    }

    /**
     * Gets query for [[Spouses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpouses()
    {
        return $this->hasMany(Spouse::className(), ['person_id' => 'id']);
    }

    /**
     * Gets query for [[Spouses0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpouses0()
    {
        return $this->hasMany(Spouse::className(), ['spouse_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            'id' => $this->id,
            'person_kp_id' => $this->person_kp_id,
            'web_url' => $this->web_url,
            'name_ru' => $this->name_ru,
            'name_en' => $this->name_en,
            'sex' => $this->sex,
            'poster_url' => $this->poster_url,
            'growth' => $this->growth,
            'birthday' => $this->birthday,
            'death' => $this->death,
            'age' => $this->age,
            'birthplace' => $this->birthplace,
            'deathplace' => $this->deathplace,
            'has_awards' => $this->has_awards,
            'profession' => $this->profession,
        ];
    }
}
