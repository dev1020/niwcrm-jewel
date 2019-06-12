<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Products */
?>
<div class="products-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'slug',
            'category',
            'sku',
            'cost_price',
            'price',
            'description:ntext',
            'featured_image',
            'productfor',
        ],
    ]) ?>

</div>
