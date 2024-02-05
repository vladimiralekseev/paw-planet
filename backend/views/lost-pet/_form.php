<?php

use common\models\Pet;
use common\models\Review;
use common\models\upload\ReviewPetUploadForm;
use common\models\upload\ReviewUserUploadForm;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View       $this
 * @var ActiveForm $form
 * @var Pet        $model
 */
?>

<div class="text-page-form">

    <?php $form = ActiveForm::begin(
        [
            'options'        => ['enctype' => 'multipart/form-data'],
            'id'             => 'text-page',
            'layout'         => 'horizontal',
            'validateOnBlur' => false,
        ]
    ); ?>

    <?= $form->field($model, 'status')->dropDownList($model::getStatusList()) ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php if ($model->isNewRecord): ?>
                <?= Html::submitButton(
                    '<span class="glyphicon glyphicon-plus-sign"></span> Create',
                    ['class' => 'btn btn-success']
                ) ?>
            <?php else: ?>
                <?= Html::submitButton(
                    '<span class="glyphicon glyphicon-ok"></span> Save',
                    ['class' => 'btn btn-primary']
                ) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
