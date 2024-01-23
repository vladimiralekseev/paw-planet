<?php

namespace common\models\upload;

class LostPetImageUploadForm extends UploadForm
{
    public $dir = 'lost-pet-image';
    public $profile = 'lostPetImage';


    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                [['file'], 'required'],
            ]
        );
    }
}
