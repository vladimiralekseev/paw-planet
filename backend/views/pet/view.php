<?php

use common\models\Pet;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View $this
 * @var Pet  $model
 */

$this->title = $model->nickname;
$this->params['breadcrumbs'][] = ['label' => 'Pets', 'url' => ['index']];
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
                        [
                            'attribute' => 'status',
                            'value'     => static function (Pet $model) {
                                $class = 'success';
                                if ($model->status === Pet::STATUS_INACTIVE) {
                                    $class = 'warning';
                                }
                                if ($model->status === Pet::STATUS_BLOCKED) {
                                    $class = 'danger';
                                }
                                return $model->for_borrow ? '<span class="label label-' . $class . '">' .
                                    Pet::getStatusValue($model->status)
                                    . '</span>' : 'No';
                            },
                            'format'    => 'raw',
                        ],
                        'nickname',
                        'description',
                        'needs',
                        'good_with',
                        [
                            'attribute' => 'for_borrow',
                            'value'     => static function (Pet $model) {
                                return $model->for_borrow ? 'Yes' : 'No';
                            },
                        ],
                        [
                            'attribute' => 'for_walk',
                            'value'     => static function (Pet $model) {
                                return $model->for_walk ? 'Yes' : 'No';
                            },
                        ],
                        [
                            'attribute' => 'breed_id',
                            'label' => 'Breed',
                            'value'     => static function (Pet $model) {
                                return $model->breed_id ? $model->breed->name : null;
                            },
                        ],
                        [
                            'attribute' => 'user_id',
                            'label' => 'User',
                            'value'     => static function (Pet $model) {
                                return Html::a(
                                    $model->user->first_name . ' ' . $model->user->last_name,
                                    ['site-user/view', 'id' => $model->user_id]
                                );
                            },
                            'format'    => 'html',
                        ],
                        [
                            'attribute' => 'Small Image',
                            'value'     => static function (Pet $model) {
                                return $model->small_img_id ? Html::img(
                                    $model->smallImg->getUrl(),
                                    [
                                        'style' => 'max-width:300px;',
                                    ]
                                ) : null;
                            },
                            'format'    => 'html',
                        ],
                        [
                            'attribute' => 'Middle Image',
                            'value'     => static function (Pet $model) {
                                return $model->middle_img_id ? Html::img(
                                    $model->middleImg->getUrl(),
                                    [
                                        'style' => 'max-width:300px;',
                                    ]
                                ) : null;
                            },
                            'format'    => 'html',
                        ],
                        [
                            'attribute' => 'Image',
                            'value'     => static function (Pet $model) {
                                return $model->img_id ? Html::img(
                                    $model->img->getUrl(),
                                    [
                                        'style' => 'max-width:300px;',
                                    ]
                                ) : null;
                            },
                            'format'    => 'html',
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ]
            ) ?>

        </div>
    </div>
</div>
