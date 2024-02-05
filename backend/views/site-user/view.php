<?php

use common\models\SiteUser;
use common\models\UserRequestPet;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View               $this
 * @var SiteUser           $model
 * @var ActiveDataProvider $dataProviderRequestFrom
 * @var ActiveDataProvider $dataProviderRequestTo
 */

$requests = [
    [
        'label' => 'Requests of this user',
        'data'  => $dataProviderRequestFrom,
    ],
    [
        'label' => 'Requests to this user',
        'data'  => $dataProviderRequestTo,
    ],
];

$this->title = $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Site Users', 'url' => ['index']];
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
                            'value'     => static function (SiteUser $model) {
                                $class = 'success';
                                if ($model->status === SiteUser::STATUS_INACTIVE) {
                                    $class = 'warning';
                                }
                                if ($model->status === SiteUser::STATUS_DELETED) {
                                    $class = 'danger';
                                }
                                return '<span class="label label-' . $class . '">' .
                                    SiteUser::getStatusValue($model->status)
                                    . '</span>';
                            },
                            'format'    => 'raw',
                        ],
                        'first_name',
                        'last_name',
                        'email',
                        'good_with',
                        'created_at',
                        'updated_at',
                        'phone_number',
                        'about',
                        'my_location',
                        'whats_app',
                        'facebook',
                        'latitude',
                        'longitude',
                        'country',
                        'state',
                        'city',
                        'address',
                        [
                            'attribute' => 'Small Image',
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
                            'attribute' => 'Image',
                            'value'     => static function (SiteUser $model) {
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
<?php foreach ($requests as $it) { ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <label><?= $it['label'] ?></label>

            <div class="order-index">
                <?= GridView::widget(
                    [
                        'id'           => 'order-grid',
                        'dataProvider' => $it['data'],
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
                                        $model->requestOwner->fullName,
                                        ['site-user/view', 'id' => $model->request_owner_id]
                                    );
                                },
                                'format'    => 'raw',
                            ],
                            [
                                'attribute' => 'pet_id',
                                'label'     => 'Pet',
                                'value'     => static function (UserRequestPet $model) {
                                    return Html::a(
                                        $model->pet->nickname,
                                        ['pet/view', 'id' => $model->pet->id]
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
