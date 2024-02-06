<?php

namespace common\models\upload;

class ProfileSmallPreviewUploadForm extends UploadForm
{
    public const DIR = 'profile-small-preview';

    public $dir = self::DIR;
    public $profile = 'profileSmallPreview';
}
