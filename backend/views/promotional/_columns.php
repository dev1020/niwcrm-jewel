<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cust_id',
        'value'=>'cust.name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'customer_number',
		
    ],
    
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'user_id',
    ],
	[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'gender',
    ],*/
    

];   