<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use backend\models\Categories;
return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
      // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
	[
		'attribute' => 'category_id', 
		'width' => '200px',
		'value' => function ($model, $key, $index, $widget) { 
			return $model->category->category_name;
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(Categories::find()->asArray()->all(), 'category_id', 'category_name'), 
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'Any supplier'],
		'group' => true,  // enable grouping,
		'groupedRow' => true,                    // move grouped column to a single grouped row
		'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
		'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
	],
	
	/*[
		'attribute'=>'category_id', 
		'width'=>'200px',
		'value'=>function ($model, $key, $index, $widget) { 
			return $model->category->category_name;
		},
		'filterType'=>GridView::FILTER_SELECT2,
		'filter'=>ArrayHelper::map(Categories::find()->asArray()->all(), 'category_id', 'category_name'), 
		'filterWidgetOptions'=>[
			'pluginOptions'=>['allowClear'=>true],
		],
		'filterInputOptions'=>['placeholder'=>'Categories'],
		'group'=>true,  // enable grouping
		
	],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'name',
        'width'=>'200px',
        'vAlign'=>'middle',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'vAlign'=>'middle',
        'width'=>'200px',
        'attribute'=>'price',
		'value'=>function ($model, $key, $index, $widget) { 
			if($model->price_max > $model->price){
				return $model->price.' - '.$model->price_max;
			}else{
				return $model->price;
			}
			
		},
    ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip','style'=>'display:none'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip','class'=>"btn btn-primary btn-lg"],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 'class'=>"btn btn-danger btn-lg",
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   