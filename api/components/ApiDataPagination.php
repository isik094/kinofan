<?php
namespace api\components;


use yii\db\ActiveRecord;

class ApiDataPagination
{
    /**
     * @var ActiveRecord[]
     */
    public $models;
    /**
     * @var int
     */
    public $lastPage;

    /**
     * ApiDataPagination constructor.
     * @param ActiveRecord[] $models
     * @param int $lastPage
     */
    public function __construct(array $models, $lastPage)
    {
        $this->models = $models;
        $this->lastPage = $lastPage;
    }
}