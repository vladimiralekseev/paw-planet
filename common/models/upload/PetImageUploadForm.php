<?php

namespace common\models\upload;

class PetImageUploadForm extends UploadForm
{
    public $dir = 'pet-image';
    public $profile = 'petImage';


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
