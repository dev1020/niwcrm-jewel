<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersServices */
?>
<div class="customers-services-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cust_id',
            'service_id',
            'service_status',
            'service_start_time',
            'service_end_time',
        ],
    ]) ?>

</div>
