<?php

namespace common\models;

class PetDetail extends Pet
{
    public function fields(): array
    {
        return array_merge(
            parent::fields(),
            [
                'pet_images' => 'petImages',
            ]
        );
    }
}
