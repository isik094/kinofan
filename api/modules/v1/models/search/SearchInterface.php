<?php
namespace api\modules\v1\models\search;


use api\models\SearchAttributeRules;

interface SearchInterface
{
    /**
     * @return SearchAttributeRules[]
     */
    public function searchAttributes(): array;

    /**
     * @return array
     */
    public function sortAttributes(): array;
}