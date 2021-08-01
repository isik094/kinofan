<?php
namespace api\models;


class SearchAttributeRules
{
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';

    public $type;
    public $list;
    public $min;
    public $max;
    public $example;
    public $format;

    /**
     * SearchAttributeRules constructor.
     * @param string $type
     * @param array $options
     */
    public function __construct($type, array $options = [])
    {
        $this->type = $type;

        foreach ($options as $key => $value) {
            $this->$key = $value;
        }
    }
}