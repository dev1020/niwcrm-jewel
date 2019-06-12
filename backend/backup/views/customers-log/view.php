<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersLog */
?>
<div class="customers-log-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cust_id',
            'log_date',
            'start_session_time',
            'end_session_time',
            'time_spent',
            'status',
            'session_no',
        ],
    ]) ?>

</div>
