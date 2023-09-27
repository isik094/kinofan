<?php

namespace api\modules\v1\traits;

trait ProfileTrait
{
    public function profileData(): array
    {
        return [
            'surname',
            'name',
            'patronymic',
            'vk',
            'telegram',
            'sex',
            'birthday',
        ];
    }
}