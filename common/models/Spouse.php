<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spouse".
 *
 * @property int $id
 * @property int $person_id
 * @property int|null $spouse_id
 * @property int|null $divorced
 * @property string|null $divorced_reason
 * @property int|null $children
 * @property string|null $relation
 *
 * @property Person $person
 * @property Person $spouse
 */
class Spouse extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spouse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_id'], 'required'],
            [['person_id', 'spouse_id', 'divorced', 'children'], 'integer'],
            [['divorced_reason', 'relation'], 'string', 'max' => 255],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
            [['spouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['spouse_id' => 'id']],
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
            'spouse_id' => 'Spouse ID',
            'divorced' => 'Divorced',
            'divorced_reason' => 'Divorced Reason',
            'children' => 'Children',
            'relation' => 'Relation',
        ];
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

    /**
     * Gets query for [[Spouse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpouse()
    {
        return $this->hasOne(Person::className(), ['id' => 'spouse_id']);
    }
}
