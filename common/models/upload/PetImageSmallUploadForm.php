<?php

namespace common\models\upload;

class PetImageSmallUploadForm extends UploadForm
{
    public const DIR = 'pet-image-small';

    public $dir = self::DIR;
    public $profile = 'petImageSmall';
}
