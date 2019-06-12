<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersBonuses */
?>
<div class="customers-bonuses-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cust_id',
            'type',
            'order_id',
            'created_date',
            'valid_upto',
            'bonus_amount',
            'cancelled',
        ],
    ]) ?>

</div>
