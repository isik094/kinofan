<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "compilation_cinema".
 *
 * @property int $id
 * @property int|null $compilation_id
 * @property int|null $cinema_id
 *
 * @property Cinema $cinema
 * @property Compilation $compilation
 */
class CompilationCinema extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compilation_cinema';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['compilation_id', 'cinema_id'], 'integer'],
            [['cinema_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cinema::className(), 'targetAttribute' => ['cinema_id' => 'id']],
            [['compilation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Compilation::className(), 'targetAttribute' => ['compilation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'compilation_id' => 'Compilation ID',
            'cinema_id' => 'Cinema ID',
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
     * Gets query for [[Compilation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompilation()
    {
        return $this->hasOne(Compilation::className(), ['id' => 'compilation_id']);
    }
}
