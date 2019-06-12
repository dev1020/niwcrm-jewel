<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    
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
        'attribute'=>'name',
		'width'=>'250px',
		'format' => 'raw',
		'value'=>function ($data) {
            return Html::a(ucwords($data->name), ['customer-stats', 'id' => $data->id],['data-pjax'=>"0"]);
        },
		'contentOptions' => ['class' => 'custlink'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contact',
		'width'=>'250px',
		'visible' => Yii::$app->user->can('Admin'),
    ],
    
   /* [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'user_id',
    ],
	[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'gender',
    ],*/
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip','style'=>'display:none'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip','class'=>'btn btn-primary btn-lg'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 'class'=>'btn btn-danger btn-lg',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'],
		'options'=>['style'=>'width:100px'],
    ],

];   