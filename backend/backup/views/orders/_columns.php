<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

use backend\models\Customers;


return [
    
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
		'group' => true,
		'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                return [
                    'mergeColumns' => [[0,1]], // columns to merge in summary
                    'content' => [             // content to show in each summary cell
                        1 => 'Total &nbsp; ' . $model->order_date ,
                        2 => GridView::F_SUM,
                    ],
                    'contentFormats' => [      // content reformatting for each summary cell
                        2 => ['format' => 'number', 'decimals' => 2],
                    ],
                    'contentOptions' => [      // content html attributes for each summary cell
                        1 => ['style' => 'font-variant:small-caps'],
                        2 => ['style' => 'text-align:right'],
                    ],
                    // html attributes for group summary row
                    'options' => ['class' => 'info table-info','style' => 'font-size:18px;font-weight:bold;']
                ];
            },
		'contentOptions' =>['style' => 'text-align:center;vertical-align:middle;font-size:18px'],
    ],
    
	[
		'attribute'=>'cust_id', 
		'width'=>'310px',
		'format' => 'raw',
		'value'=>function ($model, $key, $index, $widget) { 
			//return ucwords($model->cust->name);
			return Html::a(ucwords($model->cust->name), ['view', 'id' => $model->id],['role'=>'modal-remote','data-toggle'=>'tooltip','title'=>'Order details']);
       
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
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'total_amount',
		'format' => 'raw',
		'value'=>function ($data) {
            return Html::a($data->total_amount, ['breakup', 'id' => $data->id],['role'=>'modal-remote','data-toggle'=>'tooltip','title'=>'Price Breakup']);
        },
		'contentOptions' => ['style' => 'text-align:right;font-size:16px'],
                    
    ],
    
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'due_amount',
    // ],
    
];   