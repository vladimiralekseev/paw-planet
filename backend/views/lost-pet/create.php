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

$this->title = 'Review creation';
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="text-page-create">
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
