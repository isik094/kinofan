<?php
namespace common\base;

class Model extends \yii\base\Model
{
    /**
     * @brief Валидатор даты
     * @param $attribute
     */
    public function dateValidator($attribute)
    {
        $a = strtotime($this->$attribute);
        $b = date('Y-m-d H:i:s', $a);
        if ($a !== strtotime($b)) {
            $this->addError($attribute, 'Неверный формат даты');
        }
    }
}