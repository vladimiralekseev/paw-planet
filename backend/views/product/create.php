<?php

use common\models\Product;
use yii\web\View;

/**
 * @var View    $this
 * @var Product $model
 */

$this->title = 'Creating';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Creating', 'url' => ['view', 'id' => $model->id]];
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
