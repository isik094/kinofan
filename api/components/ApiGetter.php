<?php

namespace api\components;

class ApiGetter
{
    /**
     * @var string
     */
    public $getterName;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @inheritdoc
     * @param $getterName
     * @param array $attributes
     */
    public function __construct($getterName, array $attributes = [])
    {
        $this->getterName = $getterName;
        $this->attributes = $attributes;
    }
}
