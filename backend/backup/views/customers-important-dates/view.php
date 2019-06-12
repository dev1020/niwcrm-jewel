<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CustomersImportantDates */
?>
<div class="customers-important-dates-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cust_id',
            'imp_date',
            'type',
            'title',
        ],
    ]) ?>

</div>
