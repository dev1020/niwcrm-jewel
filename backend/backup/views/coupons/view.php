<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Coupons */
?>
<div class="coupons-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coupon_id',
            'coupon_code',
            'coupon_rate',
        ],
    ]) ?>

</div>
