<?php
namespace common\base;

use api\components\Markdown;
use api\models\SearchAttributeRules;
use yii\validators\InlineValidator;
use yii\validators\NumberValidator;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

/**
 * Class ActiveRecord
 * @package common\base
 *
 * @property int $created_at if user timestamp behaviours
 * @property int $updated_at if user timestamp behaviours
 *
 * @property string $createdAt
 * @property string $updatedAt
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @brief Валидатор
     */
    public function convertDate() {}

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeValidate()
    {
        $this->convertDateToUnixTime();
        return parent::beforeValidate();
    }

    /**
     * @brief Определит самописный валидатор атрибута
     * @param $attribute
     * @return null|\yii\validators\InlineValidator
     */
    private function determineValidator($attribute)
    {
        if ($validators = $this->validators) {
            foreach ($validators as $validator) {
                if ($validator instanceof InlineValidator && in_array($attribute, $validator->attributes)) {
                    return $validator;
                }
            }
        }
        return null;
    }

    /**
     * @brief Определит валидируется ли данный атрибут указанным валидатором
     * @param string $attribute Атрибут
     * @param string $validatorMethod Валидатор
     * @return mixed|null|\yii\validators\Validator
     */
    private function isAttributeValidateByMethod($attribute, $validatorMethod)
    {
        if ($validators = $this->validators) {
            foreach ($validators as $validator) {
                if ($validator instanceof InlineValidator
                    && is_array($validator->method)
                    && isset($validator->method[1])
                    && $validator->method[1] == $validatorMethod
                    && in_array($attribute, $validator->attributes)
                ) {
                    return $validator;
                }
            }
        }
        return null;
    }

    /**
     * @brief Конвертация даты в unixtime
     */
    public function convertDateToUnixTime()
    {
        foreach ($this as $attribute => $value) {
            if ($this->isAttributeValidateByMethod($attribute, 'convertDate')) {
                if ($this->$attribute && is_string($this->$attribute)) {
                    $this->$attribute = strtotime($this->$attribute);
                }
            }
        }
    }

    /**
     * @brief Конвертация даты из unixtime в строковое значение
     * @param string $format
     */
    public function convertDateFromUnixTime($format = 'd.m.Y')
    {
        foreach ($this as $attribute => $value) {
            if ($this->isAttributeValidateByMethod($attribute, 'convertDate')) {
                if (is_int($this->$attribute)) {
                    $this->$attribute = date($format, $this->$attribute);
                }
            }
        }
    }

    /**
     * @brief SELECT FOR UPDATE QUERY
     * @param array|int $condition
     * @return static
     * @throws \Exception
     */
    public static function findOneForUpdate($condition)
    {
        if (is_string($condition)) {
            if ($condition === (string)(int)$condition) {
                $condition = (int)$condition;
            }
        }

        $query = static::find();
        if (is_array($condition) || is_string($condition)) {
            $query->where($condition);
        } elseif (is_int($condition)) {
            $query->where(['id' => $condition]);
        } else {
            throw new \Exception('Wrong condition');
        }

        $sql = $query->createCommand()->rawSql;

        return static::findBySql($sql . ' FOR UPDATE')->one();
    }

    /**
     * @return bool|null|string
     */
    public function getCreatedAt()
    {
        return $this->created_at ? date('d.m.Y H:i:s', $this->created_at) : null;
    }

    /**
     * @return bool|null|string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at ? date('d.m.Y H:i:s', $this->updated_at) : null;
    }

    /**
     * @brief Строгое сохранение модели. В случае наличия ошибок валидации будет выброшено исключение
     * @param $runValidation
     * @return bool
     * @throws \Exception
     */
    public function saveStrict($runValidation = true)
    {
        if (!$this->save($runValidation)) {
            throw new \Exception('Can not saved model ' . $this->className() . '. Validate error: ' . json_encode($this->errors));
        }

        return true;
    }

    /**
     * @brief Строгая валидация модели. В случае наличия ошибок валидации будет выброшено исключение
     * @return bool
     * @throws \Exception
     */
    public function validateSearch()
    {
        if (!$this->validate()) {
            throw new BadRequestHttpException('Error in search model ' . $this->className() . ': ' . json_encode($this->errors));
        }

        return true;
    }

    /**
     * @brief Загрузка поисковы параметров
     * @param array $params
     */
    public function loadSearchParams(array $params)
    {
        $attributes = $this->safeAttributes();
        foreach ($params as $attr => $value) {
            if (in_array($attr, $attributes)) {
                $this->$attr = $value;
            }
        }
    }

    /**
     * @brief Проверка на то, что загружен файл в указанном расширении
     * @param string $attribute
     * @param array $params
     */
    public function extensionValidator($attribute, array $params = [])
    {
        if (is_array($this->$attribute)) {
            foreach ($this->$attribute as $file) {
                if (!($file instanceof UploadedFile)) {
                    return;
                }

                if (!in_array($file->extension, $params)) {
                    $this->addError($attribute, 'Разрешена загрузка файлов только со следующими расширениями: ' . implode(', ', $params));
                }
            }
        } elseif ($this->$attribute instanceof UploadedFile) {
            if (!in_array($this->$attribute->extension, $params)) {
                $this->addError($attribute, 'Разрешена загрузка файлов только со следующими расширениями: ' . implode(', ', $params));
            }
        } else {
            $this->addError($attribute, 'Не является файлом');
        }
    }

    /**
     * @brief Атрибуты, по которым допустима сортировка
     * @return array
     */
    public function sortAttributes()
    {
        return [];
    }

    /**
     * @brief Атрибуты, по которым допустима фильтрация
     * @return SearchAttributeRules[]
     */
    public function searchAttributes()
    {
        return [];
    }

    /**
     * @brief Перевод в markdown
     * @param string $attribute
     * @return string|string[]|null
     */
    public function markdown($attribute)
    {
        return Markdown::convert($this->$attribute);
    }

    /**
     * @brief Форматирует данные в объекте в соответсвтии в с типами
     */
    public function formatter()
    {
        foreach ($this->getActiveValidators() as $validator) {
            if ($validator instanceof NumberValidator) {
                if ($validator->integerOnly === false) {
                    foreach ($validator->attributes as $attribute) {
                        $this->$attribute = $validator->integerOnly ? (int)$this->$attribute : (float)$this->$attribute;
                    }
                }
            }
        }
    }
}
