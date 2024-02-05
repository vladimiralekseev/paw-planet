<?php

use backend\models\search\SiteUserSearch;
use common\models\SiteUser;
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
 * @var SiteUserSearch     $searchModel
 */

$this->title = 'Site Users';
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
                            'value'     => static function (SiteUser $model) {
                                return Html::a($model->id, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                            },
                            'format'    => 'raw',
                        ],
                        [
                            'class'        => StatusColumn::class,
                            'attribute'    => 'status',
                            'optionsArray' => [
                                [10, $searchModel::getStatusValue(10), 'success'],
                                [9, $searchModel::getStatusValue(9), 'warning'],
                                [0, $searchModel::getStatusValue(0), 'danger'],
                            ],
                        ],
                        'first_name',
                        'last_name',
                        'email',
                        [
                            'attribute' => 'Image',
                            'value'     => static function (SiteUser $model) {
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
