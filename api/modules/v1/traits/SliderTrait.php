<?php

namespace api\modules\v1\traits;

use api\components\ApiFunction;

trait SliderTrait
{
    public function sliderData(): array
    {
        return [
            'id',
            'title',
            'description',
            'created_at',
            'slider_object' => new ApiFunction('getSliderList'),
        ];
    }
}