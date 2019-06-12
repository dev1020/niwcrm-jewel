<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\models\Categories;
use common\models\User;
use kartik\grid\GridView;
//use kartik\date\DatePicker;
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
	
   /*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'bpost_id',
    ],*/
	[
		'class' => '\kartik\grid\ExpandRowColumn',
		//'label' => 'Start date - End Date',
         'value'=> function ($model, $key, $index) { 
				return GridView::ROW_COLLAPSED;
		},
		'detail'=> '<div> <h1>hi </h1></div>' ,
		
    ],
	
	[
		'attribute'=>'bpost_reffered_by', 
		//'width'=>'310px',
		'value'=>function ($model, $key, $index, $widget) { 
			return isset($model->bpostRefferedBy->username)? $model->bpostRefferedBy->username : 'NA';			
		},
		'filterType'=>GridView::FILTER_SELECT2,
		'filter'=>$usersdata, 
		'filterWidgetOptions'=>[
			'pluginOptions'=>['allowClear'=>true],
		],
		'filterInputOptions'=>['placeholder'=>' Employees ..'],
		'group'=>true,  // enable grouping
	],
	[
		'label' => 'Start date - End Date',
         'attribute'=>'bpost_created_at',
         'value'=>'bpost_created_at',
		 'format'=>'raw',
		 'filterType'=>GridView::FILTER_DATE_RANGE,
		 
		'filterWidgetOptions' =>([
                'model'=>$searchModel,
                'attribute'=>'bpost_created_at',                
                'convertFormat'=>true,                
                'pluginOptions'=>[                                          
					'locale'=>['format' => 'Y-m-d'],
                ]
            ])
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'bpost_title',
    ],
    
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'bpost_place_id',
		'value'=>function ($model, $key, $index, $widget) { 
			return isset($model->bpostRefferedBy->username)? $model->bpostRefferedBy->username : 'NA';			
		},
    ],*/
	[
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'bpost_phone',
     ],
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'bpost_description',
    // ],
	
    
	[
         'attribute'=>'bpost_updated_at',
         'value'=>'bpost_updated_at',
		 'format'=>'raw',
		 'filterType'=>GridView::FILTER_DATE,
		 
		'filterWidgetOptions'=>[
			'pluginOptions' => [
					'format' => 'yyyy-mm-d',
					'autoclose'=>true,
				]
		],
		'filterInputOptions'=>['placeholder'=>'Enter date ...'],
    ],
	
	
     
    
     

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
		'template' => '',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        }, 
		'visible' => false,
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   