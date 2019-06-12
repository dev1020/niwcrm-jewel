<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Categories */
?>
<div class="categories-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'category_id',
            'category_root',
            'category_name',
            'category_status',
            'category_pic',
            'category_slug',
        ],
    ]) ?>

</div>
