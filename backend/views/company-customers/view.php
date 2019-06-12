<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CompanyCustomers */
?>
<div class="company-customers-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'cust.name',
            'created_date',
            'customer_number',
			[                      // the owner name of the model
            'label' => 'Introducer ',
            'value' => isset($model->introducer->name)? $model->introducer->name : 'N.A',
			],
			
        ],
    ]) ?>

</div>
