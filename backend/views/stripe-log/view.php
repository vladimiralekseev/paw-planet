<?php

use common\models\Color;
use common\models\StripeLog;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View  $this
 * @var StripeLog $model
 */

$this->title = $model->event . ' ' . $model->siteUser->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Stripe Events', 'url' => ['index']];
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
                <?= Html::a(
                    'Create',
                    ['create'],
                    ['class' => 'btn btn-sm btn-success']
                ) ?>

                <?= Html::a(
                    'Delete',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-sm btn-danger pull-right',
                        'data'  => [
                            'confirm' => 'Are you sure you want to delete this text page?',
                            'method'  => 'post',
                        ],
                    ]
                ) ?>
            </p>

            <?= DetailView::widget(
                [
                    'model'      => $model,
                    'attributes' => [
                        'id',
                        'event',
                        [
                            'attribute' => 'site_user_id',
                            'label' => 'User',
                            'value'     => static function (StripeLog $model) {
                                return $model->siteUser->fullName;
                            },
                            'format'    => 'html',
                        ],
                        [
                            'attribute' => 'data',
                            'label' => 'Data',
                            'value'     => static function (StripeLog $model) {
                                return '<pre>' . $model->data . '</pre>';
                            },
                            'format'    => 'html',
                        ],
                        'created_at',
                    ],
                ]
            ) ?>

        </div>
    </div>
</div>
