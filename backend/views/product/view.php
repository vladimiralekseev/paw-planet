<?php

use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View               $this
 * @var Product            $model
 * @var ActiveDataProvider $dataProviderRequest
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="text-page-view">

    <div class="panel panel-default">
        <div class="panel-body">

            <p>
                <?= Html::a(
                    'Edit',
                    ['update', 'id' => $model->id],
                    ['class' => 'btn btn-sm btn-primary']
                ) ?>
            </p>

            <?= DetailView::widget(
                [
                    'model'      => $model,
                    'attributes' => [
                        'id',
                        'name',
                        [
                            'attribute' => 'status',
                            'value'     => static function (Product $model) {
                                $class = 'success';
                                if ($model->status === Product::STATUS_INACTIVE) {
                                    $class = 'warning';
                                }
                                return '<span class="label label-' . $class . '">' .
                                    Product::getStatusValue($model->status)
                                    . '</span>';
                            },
                            'format'    => 'raw',
                        ],
                        [
                            'attribute' => 'type',
                            'value'     => static function (Product $model) {
                                return Product::getTypeValue($model->type);
                            },
                            'format'    => 'raw',
                        ],
                        [
                            'attribute' => 'period',
                            'value'     => static function (Product $model) {
                                return Product::getPeriodValue($model->period);
                            },
                            'format'    => 'raw',
                        ],
                        'stripe_product_id',
                        'amount',
                    ],
                ]
            ) ?>

        </div>
    </div>
</div>

