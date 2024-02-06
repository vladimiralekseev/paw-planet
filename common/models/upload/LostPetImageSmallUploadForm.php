<?php

namespace common\models\upload;

class LostPetImageSmallUploadForm extends UploadForm
{
    public const DIR = 'lost-pet-image-small';

    public $dir = self::DIR;
    public $profile = 'lostPetImageSmall';
}
