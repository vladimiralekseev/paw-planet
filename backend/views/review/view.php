<?php

use common\models\Blog;
use common\models\Review;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/**
 * @var View          $this
 * @var Review          $model
 * @var string[]|null $blogSections
 * @var string[]|null $blogTags
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
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
                        'name',
                        'short_description',
                        'description',
                        [
                            'attribute'  => 'User Image',
                            'value'  => $model->user_img_id ? Html::img(
                                $model->userImg->getUrl(),
                                [
                                    'style' => 'max-width:300px;',
                                ]
                            ) : null,
                            'format' => 'html',
                        ],
                        [
                            'label'  => 'Pet Image',
                            'value'  => $model->pet_img_id ? Html::img(
                                $model->petImg->getUrl(),
                                [
                                    'style' => 'max-width:300px;',
                                ]
                            ) : null,
                            'format' => 'html',
                        ],
                        'date',
                        'created_at',
                        'updated_at',
                    ],
                ]
            ) ?>

        </div>
    </div>
</div>
