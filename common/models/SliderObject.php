<?php

namespace common\models;

/**
 * This is the model class for table "slider_object".
 *
 * @property int $id
 * @property int $slider_id
 * @property string|null $image_url
 * @property string|null $url
 * @property string|null $entity
 * @property int|null $entity_id
 *
 * @property Slider $slider
 */
class SliderObject extends \common\base\ActiveRecord
{
    public const CINEMA = 'cinema';
    public const PERSON = 'person';
    public const SELECTION = 'selection';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'slider_object';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['slider_id'], 'required'],
            [['slider_id', 'entity_id'], 'integer'],
            [['image_url', 'url', 'entity'], 'string', 'max' => 255],
            ['entity', 'in', 'range' => [self::CINEMA, self::PERSON, self::SELECTION]],
            [['slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Slider::className(), 'targetAttribute' => ['slider_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'slider_id' => 'Slider ID',
            'image_url' => 'Image Url',
            'url' => 'Url',
            'entity' => 'Entity',
            'entity_id' => 'Entity ID',
        ];
    }

    /**
     * Gets query for [[Slider]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSlider(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Slider::className(), ['id' => 'slider_id']);
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function getEntityObject(): ?array
    {
        $object = null;
        if ($this->entity === self::CINEMA) {
            $object = Cinema::findOne($this->entity_id);
        }

        if ($this->entity === self::PERSON) {
            $object = Person::findOne($this->entity_id);
        }

        if ($this->entity === self::SELECTION) {
            $object = Selection::findOne($this->entity_id);
        }

        return $object?->getData();
    }
}
