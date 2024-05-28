<?php

use common\models\Product;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View       $this
 * @var ActiveForm $form
 * @var Product    $model
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

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'amount') ?>
    <?= $form->field($model, 'stripe_product_id')
        ->textInput(['disabled' => !$model->isNewRecord])
        ->hint('Product ID from stripe.com. E.g.: <b>prod_QAGS7cehmHjFf1</b>') ?>
    <?= $form->field($model, 'type')->dropDownList($model::getTypeList()) ?>
    <?= $form->field($model, 'period')->dropDownList($model::getPeriodList()) ?>
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
