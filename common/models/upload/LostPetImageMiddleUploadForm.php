<?php

namespace common\models\upload;

class LostPetImageMiddleUploadForm extends UploadForm
{
    public const DIR = 'lost-pet-image-middle';

    public $dir = self::DIR;
    public $profile = 'lostPetImageMiddle';
}
