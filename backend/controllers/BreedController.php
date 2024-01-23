<?php

namespace backend\controllers;

use backend\models\search\BreedSearch;
use common\models\Breed;

class BreedController extends CrudController
{
    use UploadFileTrait;

    public $modelClass = Breed::class;
    public $modelSearchClass = BreedSearch::class;
}
