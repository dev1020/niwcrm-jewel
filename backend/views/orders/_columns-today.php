<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

use backend\models\Customers;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
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
        'attribute'=>'order_date',
		'format' => 'raw',
		'value'=>function ($data) {
            return $data->order_date;
        },
		'filterType' => GridView::FILTER_DATE,
		'filterWidgetOptions' => [
			'pluginOptions' => [
				'format' => 'yyyy-mm-dd',
				'autoclose' => true,
				'todayHighlight' => true,
			]
		],
		
		'group' => true,
		'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                return [
                    'mergeColumns' => [[0,4],[5],[6,10]], // columns to merge in summary
                    'content' => [             // content to show in each summary cell
                        1 => 'Total &nbsp; ' . date('d-M-Y',strtotime($model->order_date)) ,
                        5 => GridView::F_SUM,
                        6 => GridView::F_SUM,
                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                        5 => ['format' => 'number', 'decimals' => 2],
                        6 => ['format' => 'number', 'decimals' => 6],
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        1 => ['style' => 'font-variant:small-caps'],
                        5 => ['style' => 'text-align:left'],
                        6 => ['style' => 'text-align:left'],
                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info','style' => 'font-size:18px;font-weight:bold;']
                ];
            },
		'contentOptions' =>['style' => 'text-align:center;vertical-align:middle;font-size:18px'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cust_id',
		'value'=>function ($data) {
             if(isset($data->cust->name)){
				 return $data->cust->name;
			 }else{
				 return $data->cust->contact;
			 }
        },
		'filterType'=>GridView::FILTER_SELECT2,
		'filter'=>ArrayHelper::map(Customers::find()->asArray()->all(), 'id', 'name'), 
		'filterWidgetOptions'=>[
			'pluginOptions'=>['allowClear'=>true],
		],
		'filterInputOptions'=>['placeholder'=>'Customers'],
		'group'=>false,  // enable grouping
		'contentOptions' =>['style' => 'text-align:center;font-size:18px'],
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'session_nos',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'total_amount',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_by',
    // ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'weight',
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'order_note',
     ],
	/*[
		'class'=>'\kartik\grid\DataColumn',
		'attribute'=>'sms_delivered',
		'width'=>'100px',
		'format'=>'raw',
		'value'=>function($model){
			if($model->sms_delivered == 'yes'){
				return '<span class="label label-success ">Yes</label>';
			}else{
				return '<span class="label label-warning ">No</label>';
			}
		}
	],*/
	[
		'class'=>'\kartik\grid\DataColumn',
		'attribute'=>'status',
		'width'=>'100px',
		'format'=>'raw',
		'value'=>function($model){
			if($model->status == 'isdue'){
				return '<span class="label label-danger ">DUE</label>';
			}else{
				return '<span class="label label-success ">PAID</label>';
			}
		}
	],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
		'width'=>'100px',
		'template' => '{view},{approval}',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
		'buttons'=>[
			'view' => function ($url, $model, $key) {
				$url = Url::toRoute(['view', 'id'=>$model->id]);
				return Html::a('<span class="fa fa-inr"></span>', $url,['role'=>'modal-remote','title'=>'Pay','data-toggle'=>'tooltip' ,'class'=>"btn btn-success"]);
			},
			'approval' => function ($url, $model, $key) {
				$url = Url::toRoute(['order-approval', 'id'=>$model->id]);
				if($model->order_approved=='no'){
					return Html::a('<span class="fa fa-check-circle"></span>', $url,['role'=>'modal-remote','title'=>'Approve','data-toggle'=>'tooltip' ,'class'=>"btn btn-danger"]);
				}else{
					return false;
				}
				
			},
		],
        
    ],

];   