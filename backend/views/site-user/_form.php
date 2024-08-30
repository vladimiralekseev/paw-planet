<?php

use common\models\Product;
use common\models\SiteUser;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View       $this
 * @var ActiveForm $form
 * @var SiteUser   $model
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

    <?= $form->field($model, 'first_name')->textInput(['disabled' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['disabled' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList($model::getStatusList())->label('User status') ?>
    <?= $form->field($model, 'product_id')->dropDownList(
        ArrayHelper::map(
            Product::find()->all(),
            'id',
            function(Product $el) {
                return $el->getStatusValue($el->status) . ' - ' . $el->name;
            }
        ),
        ['prompt' => '']
    ) ?>
    <?= $form->field($model, 'subscription_status')->dropDownList(
            $model::getSubscriptionStatusList(), ['prompt' => '']
    )->label('Subscription status') ?>
    <?= implode('<br>', $model->getErrorSummary(true)) ?>

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
