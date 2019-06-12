<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Categories;
use kartik\grid\GridView;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
      
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'category_pic',
		'format' => ['image',['width'=>'40']],    
		'value' => function ($data){
				return ($data['category_pic']!="")?(Yii::getAlias('@frontendimage').'/categorypic/'. $data['category_pic']):(Yii::getAlias('@frontendimage').'/noimage.png');
				},
		'width'=>'250px',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'category_name',
		'width'=>'250px',
    ],
	[
        'class'=>'\kartik\grid\DataColumn',
        'attribute' => 'category_root',
		'value' => 'categories.category_name',
		'width'=>'250px',
    ],
	
	
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'category_status',
		'width'=>'250px',
    ],
	/*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'description',
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
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete','class'=>'btn btn-danger btn-lg', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
		'options'=>['style'=>'width:100px'],
    ],

];   