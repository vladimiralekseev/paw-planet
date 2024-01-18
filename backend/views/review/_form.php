<?php

use common\models\Review;
use common\models\upload\ReviewPetUploadForm;
use common\models\upload\ReviewUserUploadForm;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View                 $this
 * @var ActiveForm           $form
 * @var Review               $model
 * @var ReviewPetUploadForm  $reviewPetUploadForm
 * @var ReviewUserUploadForm $reviewUserUploadForm
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

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'short_description')->textInput() ?>
    <?php
    echo $form->field($model, 'date')->widget(
        DateTimePicker::class,
        [
            'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
            'value' => date('yyyy-mm-dd hh:ii'),
            'options' => ['placeholder' => 'Select issue date ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true,
                'autoclose' => true
            ]
        ]
    );
    ?>
    <?= $form->field($model, 'description')->textArea() ?>
    <?= $this->render(
        '../components/upload-file',
        [
            'model'        => $model,
            'uploadForm'   => $reviewUserUploadForm,
            'form'         => $form,
            'label'        => 'Image User',
            'field'        => 'user_img',
            'canBeDeleted' => true,
        ]
    ) ?>
    <?= $this->render(
        '../components/upload-file',
        [
            'model'        => $model,
            'uploadForm'   => $reviewPetUploadForm,
            'form'         => $form,
            'label'        => 'Image Pet',
            'field'        => 'pet_img',
            'canBeDeleted' => true,
        ]
    ) ?>

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
