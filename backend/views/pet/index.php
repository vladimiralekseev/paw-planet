<?php

use backend\models\search\PetSearch;
use common\models\Pet;
use webvimark\components\StatusColumn;
use webvimark\extensions\GridPageSize\GridPageSize;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View               $this
 * @var ActiveDataProvider $dataProvider
 * @var PetSearch          $searchModel
 */

$this->title = 'Pets';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="text-page-index">

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-6">
                    <p>
                    </p>
                </div>

                <div class="col-sm-6 text-right">
                    <?= GridPageSize::widget(['pjaxId' => 'text-page-grid-pjax']) ?>
                </div>
            </div>

            <?php Pjax::begin(
                [
                    'id' => 'text-page-grid-pjax',
                ]
            ) ?>

            <?= GridView::widget(
                [
                    'id'           => 'text-page-grid',
                    'dataProvider' => $dataProvider,
                    'layout'       => '{items}<div class="row"><div class="col-sm-8">{pager}</div><div class="col-sm-4 text-right">{summary}</div></div>',
                    'filterModel'  => $searchModel,
                    'pager'        => [
                        'options'          => ['class' => 'pagination pagination-sm'],
                        'hideOnSinglePage' => true,
                        'lastPageLabel'    => '>>',
                        'firstPageLabel'   => '<<',
                    ],
                    'columns'      => [
                        [
                            'class'   => SerialColumn::class,
                            'options' => ['style' => 'width:10px'],
                        ],
                        [
                            'attribute' => 'id',
                            'value'     => static function (Pet $model) {
                                return Html::a($model->id, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                            },
                            'format'    => 'raw',
                        ],
                        [
                            'class' => StatusColumn::class,
                            'attribute' => 'status',
                            'optionsArray' => [
                                [1, $searchModel::getStatusValue(1), 'success'],
                                [2, $searchModel::getStatusValue(2), 'warning'],
                                [3, $searchModel::getStatusValue(3), 'danger'],
                            ],
                        ],
                        'nickname',
                        'description',
                        'needs',
                        'good_with',
                        'age',
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
                            'attribute' => 'Image',
                            'value'     => static function ($model) {
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
                            'class'          => ActionColumn::class,
                            'contentOptions' => ['style' => 'width:70px; text-align:center;'],
                        ],
                    ],
                ]
            ) ?>

            <?php Pjax::end() ?>
        </div>
    </div>
</div>
