<?php
namespace api\components;

/**
 * Используется для передачи данных без изменений
 * Class ApiUnchanged
 * @package api\components
 */
class ApiUnchanged
{
    /**
     * @var string
     */
    public $data;

    /**
     * @inheritdoc
     * @param $getterName
     * @param array $attributes
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}