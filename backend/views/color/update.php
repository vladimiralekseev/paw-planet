<?php

use common\models\Color;
use yii\web\View;

/**
 * @var View  $this
 * @var Color $model
 */

$this->title = $model->color;
$this->params['breadcrumbs'][] = ['label' => 'Review', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->color, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editing';
?>

<div class="text-page-update">

    <div class="panel panel-default">
        <div class="panel-body">
            <?= $this->render(
                '_form',
                compact(
                    'model'
                )
            ) ?>
        </div>
    </div>
</div>
