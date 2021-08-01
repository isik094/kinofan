<?php
namespace api\components;


class ApiFunction
{
    /**
     * @var string
     */
    public $function;
    /**
     * @var array
     */
    public $args;
    /**
     * @var bool
     */
    public $enableCache;
    /**
     * @var string
     */
    public $wayToObject;

    /**
     * @inheritdoc
     * @param string $function
     * @param array $args
     * @param array|string|null $wayToObject
     * @param bool $enableCache
     */
    public function __construct($function, array $args = [], $wayToObject = null, $enableCache = true)
    {
        $this->function = $function;
        $this->args = $args;
        $this->enableCache = $enableCache;
        $this->wayToObject = $wayToObject;
    }
}