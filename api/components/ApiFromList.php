<?php
namespace api\components;


class ApiFromList
{
    /**
     * @var string
     */
    public $attribute;

    /**
     * @var array
     */
    public $list = [];

    /**
     * @var bool
     */
    public $required;

    /**
     * @inheritdoc
     * @param $attribute
     * @param array $list
     */
    public function __construct($attribute, array $list, $required = false)
    {
        $this->attribute = $attribute;
        $this->list = $list;
        $this->required = $required;
    }
}