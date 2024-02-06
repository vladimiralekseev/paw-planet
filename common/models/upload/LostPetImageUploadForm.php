<?php

namespace common\models\upload;

class LostPetImageUploadForm extends UploadForm
{
    public const DIR = 'lost-pet-image';

    public $dir = self::DIR;
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
