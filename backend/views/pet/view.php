<?php

use common\models\Pet;
use common\models\UserRequestPet;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View               $this
 * @var Pet                $model
 * @var ActiveDataProvider $dataProviderRequest
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
                                return '<span class="label label-' . $class . '">' .
                                    Pet::getStatusValue($model->status)
                                    . '</span>';
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
                            'label'     => 'Breed',
                            'value'     => static function (Pet $model) {
                                return $model->breed_id ? $model->breed->name : null;
                            },
                        ],
                        [
                            'attribute' => 'user_id',
                            'label'     => 'User',
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

<h3>Images</h3>
<div class="images-om-main">
    <?php foreach ($model->petImages as $file) { ?>
        <img src="<?= $file->smallImg->url ?>" height="100" alt="" class="mr-3"/>
    <?php } ?>
</div>
<br>
<?php if ($dataProviderRequest) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <label>Requests to this pet</label>

            <div class="order-index">
                <?= GridView::widget(
                    [
                        'id'           => 'order-grid',
                        'dataProvider' => $dataProviderRequest,
                        'layout'       => "{items}\n{pager}",
                        'pager'        => [
                            'options'          => ['class' => 'pagination pagination-sm'],
                            'hideOnSinglePage' => true,
                            'lastPageLabel'    => '>>',
                            'firstPageLabel'   => '<<',
                        ],
                        'columns'      => [

                            [
                                'attribute' => 'id',
                                'value'     => static function (UserRequestPet $model) {
                                    return Html::a($model->id, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                },
                                'format'    => 'raw',
                            ],
                            [
                                'attribute' => 'request_owner_id',
                                'label'     => 'Create request',
                                'value'     => static function (UserRequestPet $model) {
                                    return Html::a(
                                        $model->requestOwner->first_name . ' ' . $model->requestOwner->last_name,
                                        ['site-user/view', 'id' => $model->request_owner_id]
                                    );
                                },
                                'format'    => 'raw',
                            ],
                            'type',
                            'status',
                            'created_at',
                            'updated_at',
                        ],
                    ]
                ) ?>
            </div>
        </div>
    </div>
<?php } ?>
