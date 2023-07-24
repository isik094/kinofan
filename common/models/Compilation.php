<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "compilation".
 *
 * @property int $id
 * @property string $name
 *
 * @property CompilationCinema[] $compilationCinemas
 */
class Compilation extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compilation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
     * Gets query for [[CompilationCinemas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompilationCinemas()
    {
        return $this->hasMany(CompilationCinema::className(), ['compilation_id' => 'id']);
    }
}
