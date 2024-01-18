<?php

use common\models\Review;
use common\models\upload\ReviewPetUploadForm;
use common\models\upload\ReviewUserUploadForm;
use yii\web\View;

/**
 * @var View                 $this
 * @var Review               $model
 * @var ReviewPetUploadForm  $reviewPetUploadForm
 * @var ReviewUserUploadForm $reviewUserUploadForm
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Review', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editing';
?>

<div class="text-page-update">

    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->render(
                '_form',
                compact(
                    'model',
                    'reviewPetUploadForm',
                    'reviewUserUploadForm'
                )
            ) ?>
        </div>
    </div>
</div>
