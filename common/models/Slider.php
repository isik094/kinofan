<?php

namespace common\models;

/**
 * This is the model class for table "slider".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $created_at
 *
 * @property SliderObject[] $sliderObjects
 */
class Slider extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'slider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 600],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[SliderObjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSliderObjects(): \yii\db\ActiveQuery
    {
        return $this->hasMany(SliderObject::className(), ['slider_id' => 'id']);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getSliderList(): array
    {
        $sliderArray = [];
        foreach ($this->sliderObjects as $sliderObject) {
            $sliderArray[] = [
                'id' => $sliderObject->id,
                'image_url' => $sliderObject->uploadsLink('image_url'),
                'url' => $sliderObject->url,
                'entity' => $sliderObject->entity,
                'entity_object' => $sliderObject->getEntityObject()
            ];
        }

        return $sliderArray;
    }
}
