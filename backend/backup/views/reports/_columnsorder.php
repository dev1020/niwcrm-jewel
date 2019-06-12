<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\models\OrderDetailsSearch;
use backend\models\Bposts;
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
		'detail'=>function ($model, $key, $index,$column) { 
			$searchModel = new OrderDetailsSearch();
			$searchModel->order_id = $model->order_id;
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
			return Yii::$app->controller->renderPartial('_orderdetails',[
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);
		},
		'expandOneOnly'=>true,	
		
    ],
	[
		'attribute'=>'reffered_by', 
		//'width'=>'310px',
		'value'=>function ($model, $key, $index, $widget) { 
			//return isset($model->bpostRefferedBy->username)? $model->bpostRefferedBy->username : 'NA';			
			return $model->reffered_by;			
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
         'attribute'=>'created_date',
         'value'=>'created_date',
		 'format'=>'raw',
		 'filterType'=>GridView::FILTER_DATE_RANGE,
		 
		'filterWidgetOptions' =>([
                'model'=>$searchModel,
                'attribute'=>'created_date',                
                'convertFormat'=>true,                
                'pluginOptions'=>[                                          
					'locale'=>['format' => 'Y-m-d'],
                ]
            ])
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'invoice_number',
    ],
    
    [
		'attribute'=>'order_bpostid', 
		//'width'=>'310px',
		'value'=>function ($model, $key, $index, $widget){ 
			//return $model->order_bpostid;	
			return isset($model->orderBpostdetail->bpost_title)? $model->orderBpostdetail->bpost_title : 'NA';	

			//return 'NA';		
		},
		'filterType'=>GridView::FILTER_SELECT2,
		'filter'=>ArrayHelper::map(Bposts::find()->asArray()->all(), 'bpost_id', 'bpost_title'), 
		'filterWidgetOptions'=>[
			'pluginOptions'=>['allowClear'=>true],
		],
		'filterInputOptions'=>['placeholder'=>' Business Posts..'],
		
	],
	
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'bpost_description',
    // ],
	
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'total_amount',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'coupon_used',
    ],
	[
		'class'=>'\kartik\grid\DataColumn',
		'attribute'=>'grand_total',
	],
	
	[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
		'format' => 'html',
		'value'=>function ($model, $key, $index, $widget){ 
			if($model->status == 'Pending'){
				return '<span class="label label-warning"><strong>'.$model->status.'</strong></span>';
			}elseif($model->status == 'Failed'){
				return '<span class="label label-danger"><strong>'.$model->status.'</strong></span>';
			}elseif($model->status == 'Processing'){
				return '<span class="label label-primary"><strong>'.$model->status.'</strong></span>';
			}elseif($model->status == 'Complete'){
				return '<span class="label label-success"><strong>'.$model->status.'</strong></span>';
			}
			
	
		},
		'contentOptions' => function ($model, $key, $index, $column) {
			
				
					return ['style' => 'background-color:#ffffff ; text-align:center; font-size:17px !important'  ];
					
				
		},
		
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