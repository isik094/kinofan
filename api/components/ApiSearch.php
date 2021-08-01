<?php
namespace api\components;


use yii\db\ActiveQuery;

class ApiSearch
{
    /**
     * @var ActiveQuery
     */
    public $query;
    /**
     * @var int
     */
    public $lastPage;

    /**
     * ApiSearchDataResponse constructor.
     * @param ActiveQuery $query
     * @param int $lastPage
     */
    public function __construct(ActiveQuery $query, $lastPage)
    {
        $this->query = $query;
        $this->lastPage = $lastPage;
    }
}