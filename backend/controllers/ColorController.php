<?php

namespace backend\controllers;

use backend\models\search\ColorSearch;
use common\models\Color;

class ColorController extends CrudController
{
    use UploadFileTrait;

    public $modelClass = Color::class;
    public $modelSearchClass = ColorSearch::class;
}
